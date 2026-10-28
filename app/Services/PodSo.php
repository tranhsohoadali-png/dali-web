<?php
namespace App\Services;

use App\Models\PodBienThe;
use App\Models\PodDon;
use App\Models\PodDonDong;
use App\Models\PodMau;
use App\Models\PodSeller;
use App\Models\PodVi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Sổ tiền + đơn của cổng seller POD. Mọi chỗ đụng tới tiền đi qua đây
 * (API seller và admin dùng chung) để giá và số dư không bao giờ lệch nhau.
 *
 * Trừ ví theo kiểu "ghi trước, kiểm sau": thêm dòng trừ rồi mới xem số dư, âm thì huỷ.
 * SQLite giữ khoá ghi từ câu INSERT đầu tiên, nên hai đơn gửi cùng lúc không thể
 * cùng tiêu một khoản tiền.
 */
class PodSo
{
    public const GIA_GRAM_MAC_DINH = 1300; // bảng giá in 3D PLA 10/2026
    public const SL_TOI_DA         = 500;

    /** Đơn giá mỗi gram (đổi ở admin, lưu cau_hinh_3d.POD_GIA_GRAM). */
    public static function giaGram(): int
    {
        $v = DB::table('cau_hinh_3d')->where('khoa', 'POD_GIA_GRAM')->value('gia_tri');
        $n = $v === null ? null : json_decode($v, true);
        return is_numeric($n) && $n > 0 ? (int) $n : self::GIA_GRAM_MAC_DINH;
    }

    public static function datGiaGram(int $gia): void
    {
        DB::table('cau_hinh_3d')->updateOrInsert(
            ['khoa' => 'POD_GIA_GRAM'],
            ['gia_tri' => json_encode($gia), 'updated_at' => now(), 'created_at' => now()]
        );
    }

    /** Giá 1 dòng: gram × SL × đơn giá, cộng phí cá nhân hoá MỘT lần cho dòng có chữ riêng. */
    public static function giaDong(float $gram, int $soLuong, int $donGia, int $phiCaNhan, bool $coChu): int
    {
        return (int) round($gram * $soLuong * $donGia) + ($coChu ? $phiCaNhan : 0);
    }

    /**
     * Kiểm + tính các dòng seller gửi lên. Phân loại thuộc mẫu seller không được thấy
     * bị báo như KHÔNG TỒN TẠI (cùng câu với mã sai) — không lộ là mẫu có thật.
     *
     * @param array<int, array{bien_the_id:mixed, so_luong:mixed, mau_sac?:mixed, chu?:mixed}> $dongIn
     */
    public static function tinhDong(PodSeller $s, array $dongIn): array
    {
        if (!$dongIn) throw ValidationException::withMessages(['dong' => 'Đơn chưa có dòng nào.']);
        if (count($dongIn) > 50) throw ValidationException::withMessages(['dong' => 'Tối đa 50 dòng mỗi đơn.']);
        $donGia = self::giaGram();
        $kq = [];
        foreach (array_values($dongIn) as $i => $d) {
            $so = $i + 1;
            $bt = PodBienThe::whereKey((int) ($d['bien_the_id'] ?? 0))->first();
            $mau = $bt ? PodMau::hienCho($s->id)->whereKey($bt->mau_id)->first() : null;
            if (!$bt || !$mau) {
                throw ValidationException::withMessages(["dong.$i" => "Dòng $so: không tìm thấy mẫu."]);
            }
            $sl = (int) ($d['so_luong'] ?? 0);
            if ($sl < 1 || $sl > self::SL_TOI_DA) {
                throw ValidationException::withMessages(["dong.$i" => "Dòng $so: số lượng từ 1 đến " . self::SL_TOI_DA . '.']);
            }
            $mauSac = trim((string) ($d['mau_sac'] ?? ''));
            $dsMau = $bt->dsMau();
            if ($dsMau && !in_array($mauSac, $dsMau, true)) {
                throw ValidationException::withMessages(["dong.$i" => "Dòng $so: chọn một màu có sẵn."]);
            }
            $chu = mb_substr(trim((string) ($d['chu'] ?? '')), 0, 300);
            $phi = (int) $mau->phi_ca_nhan;
            $kq[] = [
                'mau_id'       => $mau->id,
                'bien_the_id'  => $bt->id,
                'ten_mau'      => $mau->ten,
                'ten_bien_the' => $bt->ten,
                'mau_sac'      => $mauSac !== '' ? mb_substr($mauSac, 0, 60) : null,
                'so_luong'     => $sl,
                'gram'         => (float) $bt->gram,
                'don_gia_gram' => $donGia,
                'chu_ca_nhan'  => $chu !== '' ? $chu : null,
                'phi_ca_nhan'  => $chu !== '' ? $phi : 0,
                'thanh_tien'   => self::giaDong((float) $bt->gram, $sl, $donGia, $phi, $chu !== ''),
            ];
        }
        return $kq;
    }

    /** Tạo đơn rồi thử trừ ví ngay. Đủ tiền -> Chờ in; thiếu -> Chờ nạp tiền. */
    public static function datDon(PodSeller $s, array $dongIn, ?array $noiGiao, ?string $maDonSan, ?string $ghiChu, ?UploadedFile $nhan): PodDon
    {
        $dong = self::tinhDong($s, $dongIn);
        $don = DB::transaction(function () use ($s, $dong, $noiGiao, $maDonSan, $ghiChu) {
            $don = PodDon::create([
                'ma'         => PodDon::taoMa(),
                'seller_id'  => $s->id,
                'noi_giao'   => $noiGiao,
                'ma_don_san' => $maDonSan,
                'ghi_chu'    => $ghiChu,
                'trang_thai' => 'cho_nap',
                'tong_tien'  => array_sum(array_column($dong, 'thanh_tien')),
            ]);
            foreach ($dong as $d) PodDonDong::create($d + ['don_id' => $don->id]);
            return $don;
        });
        if ($nhan) {
            $ext = strtolower($nhan->getClientOriginalExtension() ?: 'jpg');
            $rel = $nhan->storeAs('pod/nhan', $don->ma . '.' . $ext, 'local'); // đĩa PRIVATE: nhãn có địa chỉ khách cuối
            $don->update(['nhan' => $rel]);
        }
        if (self::thuTru($don)) $don->update(['trang_thai' => 'cho_in']);
        return $don->fresh();
    }

    /** Thử trừ ví cho một đơn Chờ nạp tiền. Trả true nếu đã trừ. */
    public static function thuTru(PodDon $don): bool
    {
        if ($don->da_tru || $don->trang_thai !== 'cho_nap') return false;
        try {
            DB::transaction(function () use ($don) {
                PodVi::create([
                    'seller_id' => $don->seller_id, 'loai' => 'tru', 'so_tien' => -(int) $don->tong_tien,
                    'don_id' => $don->id, 'ghi_chu' => 'Đơn ' . $don->ma, 'nguoi_tao' => 'he_thong',
                ]);
                $soDu = (int) DB::table('pod_vi')->where('seller_id', $don->seller_id)->sum('so_tien');
                if ($soDu < 0) throw new ThieuTien();
                $don->update(['da_tru' => true]);
            });
            return true;
        } catch (ThieuTien) {
            return false;
        }
    }

    /** Nạp (hoặc điều chỉnh) ví, rồi đẩy các đơn Chờ nạp tiền vào sản xuất theo thứ tự đặt. */
    public static function napTien(PodSeller $s, int $soTien, string $loai, ?string $ghiChu): int
    {
        if (!in_array($loai, ['nap', 'dieu_chinh'], true)) throw new \InvalidArgumentException($loai);
        PodVi::create([
            'seller_id' => $s->id, 'loai' => $loai, 'so_tien' => $soTien,
            'ghi_chu' => $ghiChu, 'nguoi_tao' => 'admin',
        ]);
        return self::xuLyChoNap($s);
    }

    /** Đơn Chờ nạp tiền cũ nhất trước; gặp đơn chưa đủ tiền thì dừng (giữ đúng thứ tự). Trả số đơn đã vào sản xuất. */
    public static function xuLyChoNap(PodSeller $s): int
    {
        $n = 0;
        foreach (PodDon::where('seller_id', $s->id)->where('trang_thai', 'cho_nap')->orderBy('id')->get() as $don) {
            if (!self::thuTru($don)) break;
            $don->update(['trang_thai' => 'cho_in']);
            $n++;
        }
        return $n;
    }

    /** Huỷ đơn, hoàn tiền nếu đã trừ. Seller chỉ huỷ trước khi in; admin huỷ được cả lúc Đang in. */
    public static function huyDon(PodDon $don, string $boi): bool
    {
        $duoc = $boi === 'admin' ? ['cho_nap', 'cho_in', 'dang_in'] : ['cho_nap', 'cho_in'];
        if (!in_array($don->trang_thai, $duoc, true)) return false;
        DB::transaction(function () use ($don, $boi) {
            if ($don->da_tru) {
                PodVi::create([
                    'seller_id' => $don->seller_id, 'loai' => 'hoan', 'so_tien' => (int) $don->tong_tien,
                    'don_id' => $don->id, 'ghi_chu' => 'Huỷ đơn ' . $don->ma . ($boi === 'admin' ? ' (xưởng huỷ)' : ''),
                    'nguoi_tao' => $boi,
                ]);
            }
            $don->update(['trang_thai' => 'da_huy', 'huy_luc' => now()]);
        });
        return true;
    }

    /** Đường dẫn file nhãn trên đĩa private (cho admin tải). */
    public static function duongNhan(PodDon $don): ?string
    {
        $rel = $don->getRawOriginal('nhan');
        return $rel && Storage::disk('local')->exists($rel) ? $rel : null;
    }
}
