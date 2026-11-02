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
            if ($this->nenDai($t) < (float) $x['so']['nen_min']) throw new \InvalidArgumentException("$nhan dài quá so với đuôi đai, bớt chữ hoặc viết tắt.");
        }
        return $nam !== '' ? "$ten / $nam" : $ten;
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
