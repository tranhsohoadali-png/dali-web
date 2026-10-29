<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Mẫu POD. chu_seller_id NULL = Kho chung (mọi seller thấy), có id = Kho riêng của seller đó.
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

    /** Mẫu seller này được thấy: Kho chung + Kho riêng của chính seller, chỉ mẫu đang hiện. */
    public function scopeHienCho(Builder $q, int $sellerId): Builder
    {
        return $q->where('hien', true)->where(function ($w) use ($sellerId) {
            $w->whereNull('chu_seller_id')->orWhere('chu_seller_id', $sellerId);
        });
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

    /** Tên hiển thị của một kiểu (mẫu nhiều kiểu), vd "Nơ + hoa"; null nếu mẫu không chia kiểu. */
    public function tenKieu(?string $kieu): ?string
    {
        return $kieu !== null ? ($this->xem_truoc['kieu'][$kieu]['ten'] ?? $kieu) : null;
    }

    /** Font seller được chọn cho tên (font đầu = mặc định). Rỗng = không cho chọn. */
    public function dsFont(): array
    {
        $f = $this->xem_truoc['fonts'] ?? null;
        return is_array($f) ? array_values(array_filter($f, 'is_string')) : [];
    }

    /** Ảnh hình chiếu cho ảnh kích thước (mẫu dạng khối): 'truoc' | 'ben' -> đường dẫn đĩa private, hoặc null. */
    public function anhHinhChieu(string $mat): ?string
    {
        $rel = in_array($mat, ['truoc', 'ben'], true) ? ($this->kich_thuoc[$mat] ?? null) : null;
        return is_string($rel) && $rel !== '' ? $rel : null;
    }

    /** Có vẽ được ảnh kích thước: ít nhất một cỡ có số đo, và mẫu có ảnh xem trước theo tên hoặc ảnh hình chiếu. */
    public function coKichThuoc(): bool
    {
        if (!$this->bienThe->contains(fn ($b) => !empty($b->kich_thuoc))) return false;
        return !empty($this->xem_truoc) || $this->anhHinhChieu('truoc') !== null;
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
