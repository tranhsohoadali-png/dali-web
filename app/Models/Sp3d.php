<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Sản phẩm khu "Xưởng in 3D". Bảng riêng `sp_3d`, không dính tới `products` (tranh).
 */
class Sp3d extends Model
{
    protected $table = 'sp_3d';

    protected $fillable = [
        'slug', 'ten', 'art', 'cat', 'danh_muc_id', 'gia', 'gia_goc', 'nhan',
        'mo_ta_ngan', 'mo_ta_dai', 'mota', 'variants', 'variant_groups', 'anh',
        'khac_ten', 'dat_lam', 'sao', 'da_ban', 'kho', 'thu_tu', 'hien',
        'payment_policy', 'shipping_class',
    ];

    protected $casts = [
        'mota'           => 'array',
        'variants'       => 'array',
        'variant_groups' => 'array',
        'anh'            => 'array',
        'khac_ten' => 'boolean',
        'dat_lam'  => 'boolean',
        'hien'     => 'boolean',
        'sao'      => 'decimal:1',
    ];

    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc3d::class, 'danh_muc_id');
    }

    /** Ảnh bìa (ảnh đầu tiên), hoặc null nếu chưa có ảnh nào. */
    public function getAnhBiaAttribute(): ?string
    {
        $anh = $this->anh ?: [];
        return $anh[0] ?? null;
    }

    /** Giá hiển thị dạng "289.000đ". */
    public function getGiaHtAttribute(): string
    {
        return number_format((int) $this->gia, 0, ',', '.') . 'đ';
    }

    public function getGiaGocHtAttribute(): ?string
    {
        return $this->gia_goc ? number_format((int) $this->gia_goc, 0, ',', '.') . 'đ' : null;
    }
}
