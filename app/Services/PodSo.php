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
    /** Giá mặc định đ/gram theo chất liệu. PETG là nhựa chính của xưởng nên đứng đầu (mặc định). */
    public const CHAT_LIEU = ['PETG' => 900, 'PLA' => 1300];
    private const KHOA_GIA = ['PETG' => 'POD_GIA_GRAM_PETG', 'PLA' => 'POD_GIA_GRAM']; // POD_GIA_GRAM có từ trước = PLA
    public const SL_TOI_DA = 500;

    /** Chất liệu hợp lệ; lạ hoặc trống -> PETG. */
    public static function chatLieu(?string $c): string
    {
        $c = strtoupper(trim((string) $c));
        return isset(self::CHAT_LIEU[$c]) ? $c : 'PETG';
    }

    /** Đơn giá mỗi gram của một chất liệu (đổi ở admin, lưu cau_hinh_3d). */
    public static function giaGram(string $chatLieu = 'PETG'): int
    {
        $c = self::chatLieu($chatLieu);
        $v = DB::table('cau_hinh_3d')->where('khoa', self::KHOA_GIA[$c])->value('gia_tri');
        $n = $v === null ? null : json_decode($v, true);
        return is_numeric($n) && $n > 0 ? (int) $n : self::CHAT_LIEU[$c];
    }

    /** ['PETG' => 900, 'PLA' => 1300] theo đơn giá hiện hành. */
    public static function bangGia(): array
    {
        $kq = [];
        foreach (array_keys(self::CHAT_LIEU) as $c) $kq[$c] = self::giaGram($c);
        return $kq;
    }

    public static function datGiaGram(string $chatLieu, int $gia): void
    {
        DB::table('cau_hinh_3d')->updateOrInsert(
            ['khoa' => self::KHOA_GIA[self::chatLieu($chatLieu)]],
            ['gia_tri' => json_encode($gia), 'updated_at' => now(), 'created_at' => now()]
        );
    }

    /** Đơn giá mỗi gram của MỘT mẫu: đơn giá chung của nhựa, cộng phần tăng giá riêng của mẫu (pod_mau.tang_gia, %). */
    public static function donGia(PodMau $m, ?string $chatLieu, ?array $bangGia = null): int
    {
        $bangGia ??= self::bangGia();
        return (int) round($bangGia[self::chatLieu($chatLieu)] * (100 + (int) $m->tang_gia) / 100);
    }

    /** Giá in 1 chiếc của một phân loại (chưa gồm phí cá nhân hoá). */
    public static function giaBienThe(PodMau $m, PodBienThe $b, ?array $bangGia = null): int
    {
        return self::giaDong((float) $b->gram, 1, self::donGia($m, $b->chat_lieu, $bangGia), 0, false);
    }

    /** Giá in 1 chiếc rẻ nhất của mẫu (theo đơn giá nhựa hiện hành), null nếu chưa có phân loại. Dùng cho chữ "từ …đ" trên thẻ. */
    public static function giaTu(PodMau $m, ?array $bangGia = null): ?int
    {
        $bangGia ??= self::bangGia();
        $ds = $m->bienThe->map(fn ($b) => self::giaBienThe($m, $b, $bangGia));
        return $ds->isEmpty() ? null : (int) $ds->min();
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
        $bangGia = self::bangGia();
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
            if ($toiDa = $mau->tenToiDa($bt->kieu)) { // mẫu khắc tên: bắt buộc có tên, không quá số ký tự ô tên chứa được
                if ($chu === '') throw ValidationException::withMessages(["dong.$i" => "Dòng $so: nhập tên cần in."]);
                if (mb_strlen($chu) > $toiDa) throw ValidationException::withMessages(["dong.$i" => "Dòng $so: tên tối đa $toiDa ký tự."]);
            }
            $font = null;
            if ($dsFont = $mau->dsFont()) { // mẫu cho chọn font: phải là font trong danh sách, bỏ trống = font mặc định
                $font = trim((string) ($d['font'] ?? '')) ?: $dsFont[0];
                if (!in_array($font, $dsFont, true)) throw ValidationException::withMessages(["dong.$i" => "Dòng $so: chọn một font có sẵn."]);
            }
            $tenKieu = $mau->tenKieu($bt->kieu);
            $phi = (int) $mau->phi_ca_nhan;
            $chatLieu = self::chatLieu($bt->chat_lieu);
            $donGia = self::donGia($mau, $chatLieu, $bangGia); // đã gồm tăng giá riêng của mẫu: dòng đơn tự khớp gram × đơn giá
            $kq[] = [
                'mau_id'       => $mau->id,
                'bien_the_id'  => $bt->id,
                'ten_mau'      => $mau->ten,
                'ten_bien_the' => mb_substr(($tenKieu ? $tenKieu . ' · ' : '') . $bt->ten, 0, 120), // chụp cả kiểu: "Nơ + hoa · 3 inch"
                'font'         => $font,
                'mau_sac'      => $mauSac !== '' ? mb_substr($mauSac, 0, 60) : null,
                'so_luong'     => $sl,
                'gram'         => (float) $bt->gram,
                'chat_lieu'    => $chatLieu,
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

    /**
     * Chờ in -> Đang in -> Đã giao (kèm mã vận đơn). Không lùi trạng thái; huỷ đi đường huyDon().
     * Admin và thợ in dùng chung. Trả null nếu xong, hoặc câu báo lỗi.
     */
    public static function chuyenTrangThai(PodDon $don, string $tt, ?string $maVanDon, string $nguoi): ?string
    {
        $duoc = ['dang_in' => ['cho_in'], 'da_giao' => ['cho_in', 'dang_in']];
        if (!isset($duoc[$tt])) return 'Trạng thái không hợp lệ.';
        if (!in_array($don->trang_thai, $duoc[$tt], true)) {
            return 'Đơn đang ở "' . $don->tenTrangThai() . '", không chuyển được.';
        }
        $data = ['trang_thai' => $tt];
        if ($tt === 'dang_in') $data['nguoi_in'] = $nguoi;
        if ($tt === 'da_giao') {
            $data['giao_luc'] = now();
            $data['nguoi_giao'] = $nguoi;
            $data['ma_van_don'] = trim((string) $maVanDon) ?: $don->ma_van_don;
            if (!$don->nguoi_in) $data['nguoi_in'] = $nguoi;
        }
        $don->update($data);
        return null;
    }

    /** Đường dẫn file nhãn trên đĩa private (cho admin tải). */
    public static function duongNhan(PodDon $don): ?string
    {
        $rel = $don->getRawOriginal('nhan');
        return $rel && Storage::disk('local')->exists($rel) ? $rel : null;
    }
}
