<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tài khoản nhân viên của seller POD (bảng pod_nhan_vien). Chủ shop tự tạo trong cổng, tối đa TOI_DA người.
 * Nhân viên đặt đơn (trừ ví của shop) nhưng KHÔNG xem/nạp ví, không sửa hồ sơ shop, không quản lý nhân viên.
 */
class PodNhanVien extends Model
{
    protected $table = 'pod_nhan_vien';
    protected $guarded = [];
    protected $hidden = ['matkhau'];
    protected $casts = [
        'hien'          => 'boolean',
        'dong_y_luc'    => 'datetime',
        'dang_nhap_luc' => 'datetime',
        'doi_mk_luc'    => 'datetime',
    ];

    public const TOI_DA = 10;

    public function seller()
    {
        return $this->belongsTo(PodSeller::class, 'seller_id');
    }

    /**
     * Nhân viên đã "xoá" nhưng còn đơn cũ: giữ dòng (tên hiện trên đơn), khoá, và đổi sdt thành "#<id>"
     * để GIẢI PHÓNG số điện thoại (đăng ký/làm nhân viên nơi khác được) — "#…" không bao giờ khớp ô đăng nhập.
     */
    public function scopeConDung($q)
    {
        return $q->where('sdt', 'not like', '#%');
    }

    public function daXoa(): bool
    {
        return str_starts_with((string) $this->sdt, '#');
    }
}
