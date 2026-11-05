<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Mẫu POD. chu_seller_id NULL = Kho chung (mọi seller thấy), có id = Kho riêng của seller đó,
 * cộng các seller được cấp thêm ở bảng pod_mau_cap (duocCap).
 * MỌI truy vấn phía seller phải đi qua scopeHienCho() — đó là bộ lọc duy nhất giữ kín mẫu độc quyền.
 */
class PodMau extends Model
{
    protected $table = 'pod_mau';
    protected $guarded = [];
    protected $casts = [
        'anh'       => 'array',
        'hien'      => 'boolean',
        'xem_truoc' => 'array',
        'kich_thuoc'=> 'array',
    ];

    /** Mẫu seller này được thấy: Kho chung + mẫu riêng của chính seller + mẫu riêng được cấp, chỉ mẫu đang hiện. */
    public function scopeHienCho(Builder $q, int $sellerId): Builder
    {
        return $q->where('hien', true)->where(function ($w) use ($sellerId) {
            $w->whereNull('chu_seller_id')->orWhere(fn ($r) => $r->riengCua($sellerId));
        });
    }

    /** Mẫu riêng seller này là chủ hoặc được cấp quyền (chưa lọc hien: gọi kèm hienCho ở phía seller). */
    public function scopeRiengCua(Builder $q, int $sellerId): Builder
    {
        return $q->whereNotNull('chu_seller_id')->where(fn ($w) => $w->where('chu_seller_id', $sellerId)
            ->orWhereExists(fn ($e) => $e->selectRaw('1')->from('pod_mau_cap')
                ->whereColumn('pod_mau_cap.mau_id', 'pod_mau.id')->where('pod_mau_cap.seller_id', $sellerId)));
    }

    /** Seller được cấp thêm quyền xem + đặt mẫu riêng này (ngoài chủ mẫu). */
    public function duocCap()
    {
        return $this->belongsToMany(PodSeller::class, 'pod_mau_cap', 'mau_id', 'seller_id')->withTimestamps()->orderBy('ten');
    }

    public function bienThe()
    {
        return $this->hasMany(PodBienThe::class, 'mau_id')->orderBy('thu_tu')->orderBy('id');
    }

    public function chu()
    {
        return $this->belongsTo(PodSeller::class, 'chu_seller_id');
    }

    public function laRieng(): bool
    {
        return $this->chu_seller_id !== null;
    }

    /**
     * Mẫu khắc tên (có ảnh xem trước theo tên): đặt đơn bắt buộc có tên. Trả số ký tự tối đa, hoặc 0 nếu không phải.
     * Mẫu nhiều kiểu: kiểu có toi_da riêng (vd chân thú cưng ô tên hẹp hơn) thì dùng số đó.
     */
    public function tenToiDa(?string $kieu = null): int
    {
        $x = $this->xem_truoc;
        if (!is_array($x) || ($x['loai'] ?? '') !== 'ten') return 0;
        $k = $kieu !== null ? ($x['kieu'][$kieu] ?? null) : null;
        return max(1, (int) ($k['toi_da'] ?? $x['toi_da'] ?? 12));
    }

    /** Mẫu chữ trên đuôi đai (móc khóa Karate): đơn ghi "TÊN / NĂM", kiểm theo bề rộng chữ chứ không chỉ số ký tự. */
    public function laDai(): bool
    {
        return ($this->xem_truoc['ve'] ?? '') === 'dai';
    }

    /**
     * Chuẩn hoá + kiểm chữ mẫu đuôi đai: viết hoa, bỏ dấu, "TÊN / NĂM". Sai thì ném \InvalidArgumentException câu báo seller.
     * Công thức bề rộng = veDai (_ve_ten.blade.php) = do_dong (app Thay tên mau_moc_khoa_karate.py); số lấy từ xem_truoc.so.
     */
    public function chuanDai(string $chu): string
    {
        $x = $this->xem_truoc;
        $phan = explode('/', $chu);
        $ten = self::chuHoaKhongDau($phan[0]);
        $nam = self::chuHoaKhongDau(implode(' ', array_slice($phan, 1)));
        if (!preg_match('/[A-Z0-9]/', $ten)) throw new \InvalidArgumentException('nhập tên cần in.');
        $toiDa = (int) ($x['toi_da'] ?? 10);
        foreach ([[$ten, 'tên'], [$nam, 'chữ đuôi trái']] as [$t, $nhan]) {
            if ($t === '') continue;
            if (preg_match('/[^A-Z0-9 \-]/', $t)) throw new \InvalidArgumentException("$nhan chỉ in được chữ A–Z, số 0–9, dấu cách và dấu gạch ngang.");
            if (strlen($t) > $toiDa) throw new \InvalidArgumentException("$nhan tối đa $toiDa ký tự.");
            if ($this->nenDai($t) < (float) $x['so']['nen_min'] || !$this->vuaDai($t)) throw new \InvalidArgumentException("$nhan dài quá so với đuôi đai, bớt chữ hoặc viết tắt.");
        }
        return $nam !== '' ? "$ten / $nam" : $ten;
    }

    /**
     * Tham số tạo file in ở app Thay tên cho một dòng đơn: {mau, ten, tuy_chon}. Null nếu mẫu chưa nối app, hoặc cỡ của
     * dòng không có trong bảng (cỡ đã đổi tên sau khi đặt) — khi đó thợ vẫn làm tay theo dòng hướng dẫn file_in.
     * xem_truoc.thay_ten = {mau: id mẫu app, co: {tên phân loại: giá trị tuỳ chọn | {tuỳ chọn: giá trị} (dấu bánh nhiều kiểu:
     * {"kieu": .., "in_gi": ..} theo "Kiểu · phân loại")}, khoa?: tên tuỳ chọn của app nhận giá trị đơn (mặc định kich_thuoc;
     * dấu bánh: in_gi), mau_dai?: {màu trong đơn: giá trị tuỳ chọn mau_dai}}. Có bảng mau_dai mà màu của dòng
     * không có trong bảng: cũng null, in sai màu đai là bỏ cả chiếc. Chỉ mẫu đuôi đai mới tách "TÊN / NĂM" (tuỳ chọn nam).
     */
    public function thamSoFileIn(?string $tenCo, ?string $chu, ?string $mauSac = null): ?array
    {
        $tt = $this->xem_truoc['thay_ten'] ?? null;
        $co = $tt['co'][(string) $tenCo] ?? null;
        if (empty($tt['mau']) || $co === null || trim((string) $chu) === '') return null;
        $khoa = (string) ($tt['khoa'] ?? 'kich_thuoc');
        $tc0 = is_array($co) ? array_map('strval', $co) : [$khoa => (string) $co];
        if (!$this->laDai()) return ['mau' => $tt['mau'], 'ten' => trim((string) $chu), 'tuy_chon' => $tc0];
        $p = array_map('trim', explode('/', (string) $chu, 2));
        $tc = $tc0 + ['nam' => $p[1] ?? ''];
        if (!empty($tt['mau_dai'])) {
            $dai = $tt['mau_dai'][(string) $mauSac] ?? null;
            if ($dai === null) return null;
            $tc['mau_dai'] = (string) $dai;
        }
        return ['mau' => $tt['mau'], 'ten' => $p[0], 'tuy_chon' => $tc];
    }

    /** Mẫu dấu bánh quy (tên chạy cung dưới mặt dấu, app Thay tên mẫu dau_banh). */
    public function laDauBanh(): bool
    {
        return ($this->xem_truoc['ve'] ?? '') === 'dau_banh';
    }

    /**
     * Chuẩn hoá + kiểm tên mẫu dấu bánh: in hoa, GIỮ dấu tiếng Việt, chỉ ký tự có trong bảng bề rộng (xem_truoc.chars), cung tên ở
     * cỡ app sẽ dựng không quá xem_truoc.so.cung_max. Sai thì ném \InvalidArgumentException câu báo seller.
     * Công thức = kiemDauBanh (_ve_ten.blade.php) = mau/dau_banh/dau_banh.scad của app Thay tên (bảng chars/adv chép từ đó).
     */
    public function chuanDauBanh(string $chu, ?string $kieu = null): string
    {
        $x = $this->xemTruocKieu($kieu);
        $t = trim(preg_replace('/\s+/u', ' ', $chu));
        if (class_exists(\Normalizer::class)) $t = \Normalizer::normalize($t, \Normalizer::FORM_C) ?: $t;
        $t = mb_strtoupper($t, 'UTF-8');
        if (!preg_match('/[\p{L}\p{N}]/u', $t)) throw new \InvalidArgumentException('nhập tên cần in.');
        $la = array_values(array_unique(array_filter(mb_str_split($t), fn ($c) => mb_strpos($x['chars'], $c) === false)));
        $mat = (string) ($x['ten_mat'] ?? 'mặt dấu');   // mẫu khác dùng chung bộ tên cung dưới (lót ly) ghi tên mặt in riêng
        if ($la) throw new \InvalidArgumentException("$mat chưa in được ký tự " . implode(' ', $la) . ". Chỉ nhận chữ cái tiếng Việt / tiếng Anh, số 0–9, dấu cách và & ' . , - !");
        $toiDa = (int) ($x['toi_da'] ?? 24);
        if (mb_strlen($t) > $toiDa) throw new \InvalidArgumentException("tên tối đa $toiDa ký tự.");
        if ($this->cungDauBanh($t, $kieu) > (float) $x['so']['cung_max'] + 0.01) throw new \InvalidArgumentException("tên dài quá so với cung dưới $mat, bớt chữ hoặc viết tắt.");
        return $t;
    }

    /** Độ dài cung (độ) của tên dấu bánh ở cỡ chữ app sẽ dựng: thu nhỏ cho vừa cung_max nhưng không dưới co_min. */
    public function cungDauBanh(string $t, ?string $kieu = null): float
    {
        $x = $this->xemTruocKieu($kieu);
        $so = $x['so'];
        $tong = 0.0;
        foreach (mb_str_split($t) as $c) {
            $i = mb_strpos($x['chars'], $c);
            $tong += $i === false ? 0.62 : (float) $x['adv'][$i];
        }
        $k = (float) $so['he_so_dan'] * max($tong, 0.01) / 0.72;
        $th = deg2rad((float) $so['cung_max']);
        $c = (float) $so['cap'] / (2 * 0.72);
        $co = max((float) $so['co_min'], min((float) $so['co'], $th * $so['r_ten'] / ($k + $th * $c) - 0.001));
        return rad2deg($k * $co / ($so['r_ten'] - $c * $co));
    }

    /**
     * xem_truoc của một kiểu: cấp ngoài gộp xem_truoc.kieu[$kieu] đè lên (như khung() của _ve_ten.blade.php với ve=dau_banh) —
     * lót ly nhiều kiểu có số xếp tên (so) riêng từng kiểu, vd Santa tên nằm trên băng rôn ngắn hơn.
     */
    private function xemTruocKieu(?string $kieu): array
    {
        $x = (array) $this->xem_truoc;
        $k = $kieu !== null ? ($x['kieu'][$kieu] ?? null) : null;
        return is_array($k) ? array_merge($x, $k) : $x;
    }

    /** Chữ vẽ tay co được tới glyph.wmin (thân 3 lớp in): tổng bề rộng tối thiểu + khe vẫn phải vừa đuôi. Bản sao vuaDai (JS) + vua_dong (app). */
    private function vuaDai(string $txt): bool
    {
        $so = $this->xem_truoc['so'];
        $gl = $this->xem_truoc['glyph'];
        $tong = 0.0;
        $n = 0;
        foreach (str_split(str_replace(' ', '', $txt)) as $c) {
            $tong += (float) ($gl[$c]['wmin'] ?? 0);
            $n++;
        }
        return $tong + $so['khe'] * ($n - 1) + 2 * $so['no_x'] * $n + substr_count($txt, ' ') * $so['khoang'] <= $so['l_max'] + 1e-9;
    }

    /** Hệ số nén dọc dòng để $txt vừa đuôi đai (1 = không nén). */
    private function nenDai(string $txt): float
    {
        $so = $this->xem_truoc['so'];
        $gl = $this->xem_truoc['glyph'];
        $tong = 0.0;
        $n = 0;
        foreach (str_split(str_replace(' ', '', $txt)) as $c) {
            $tong += (float) ($gl[$c]['w'] ?? 0);
            $n++;
        }
        $coDinh = $so['khe'] * ($n - 1) + 2 * $so['no_x'] * $n + substr_count($txt, ' ') * $so['khoang'];
        return $tong > 0 ? min(1.0, ($so['l_max'] - $coDinh) / $tong) : 1.0;
    }

    private static function chuHoaKhongDau(string $s): string
    {
        return trim(preg_replace('/\s+/', ' ', strtoupper(\Illuminate\Support\Str::ascii($s))));
    }

    /** Tên hiển thị của một kiểu (mẫu nhiều kiểu), vd "Nơ + hoa"; null nếu mẫu không chia kiểu. Mẫu khối lấy tên ở kich_thuoc.kieu. */
    public function tenKieu(?string $kieu): ?string
    {
        if ($kieu === null) return null;
        return $this->xem_truoc['kieu'][$kieu]['ten'] ?? $this->kich_thuoc['kieu'][$kieu]['ten'] ?? $kieu;
    }

    /** Font seller được chọn cho tên (font đầu = mặc định). Rỗng = không cho chọn. */
    public function dsFont(): array
    {
        $f = $this->xem_truoc['fonts'] ?? null;
        return is_array($f) ? array_values(array_filter($f, 'is_string')) : [];
    }

    /**
     * Ảnh riêng của mẫu dạng khối -> đường dẫn đĩa private, hoặc null. $mat: 'truoc' | 'ben' (hình chiếu cho ảnh
     * kích thước) | 'bia' (ảnh chính của một kiểu). Mẫu nhiều kiểu: kich_thuoc.kieu[<khoá>][$mat], thiếu thì lấy cấp ngoài.
     */
    public function anhHinhChieu(string $mat, ?string $kieu = null): ?string
    {
        if (!in_array($mat, ['truoc', 'ben', 'bia'], true)) return null;
        $k = (array) ($this->kich_thuoc ?: []);
        $rel = ($kieu !== null ? ($k['kieu'][$kieu][$mat] ?? null) : null) ?? ($k[$mat] ?? null);
        return is_string($rel) && $rel !== '' ? $rel : null;
    }

    /** Khoá các kiểu có ảnh riêng (mẫu khối nhiều kiểu), theo thứ tự lưu. */
    public function kieuCoAnh(): array
    {
        return array_keys(array_filter((array) ($this->kich_thuoc['kieu'] ?? []), 'is_array'));
    }

    /** Có vẽ được ảnh kích thước: ít nhất một cỡ có số đo, và mẫu có ảnh xem trước theo tên hoặc ảnh hình chiếu. */
    public function coKichThuoc(): bool
    {
        if (!$this->bienThe->contains(fn ($b) => !empty($b->kich_thuoc))) return false;
        if (!empty($this->xem_truoc) || $this->anhHinhChieu('truoc') !== null) return true;
        foreach ($this->kieuCoAnh() as $k) if ($this->anhHinhChieu('truoc', $k) !== null) return true;
        return false;
    }

    /** Link công khai ảnh thứ $i (mẫu Kho chung) kèm ?v= thời điểm sửa: đổi ảnh là đổi link, trình duyệt khỏi giữ ảnh cũ 24 giờ. */
    public function urlAnh(int $i = 0): string
    {
        return "/anh-mau/{$this->ma}/$i?v=" . ($this->updated_at?->timestamp ?? 0);
    }

    /** Mã 10 ký tự ngẫu nhiên. */
    public static function taoMa(): string
    {
        $chu = 'abcdefghjkmnpqrstuvwxyz23456789';
        do {
            $ma = '';
            for ($i = 0; $i < 10; $i++) $ma .= $chu[random_int(0, strlen($chu) - 1)];
        } while (static::where('ma', $ma)->exists());
        return $ma;
    }
}
