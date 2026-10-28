<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Nhật ký thao tác của admin trên mẫu POD (nhất là mẫu riêng). Bảng pod_nhat_ky. */
class PodNhatKy extends Model
{
    protected $table = 'pod_nhat_ky';
    protected $guarded = [];

    public static function ghi(?int $mauId, string $hanhDong, ?string $chiTiet = null): void
    {
        static::create([
            'mau_id'    => $mauId,
            'hanh_dong' => $hanhDong,
            'chi_tiet'  => $chiTiet !== null ? mb_substr($chiTiet, 0, 500) : null,
            'nguoi'     => 'admin',
            'ip'        => request()?->ip(),
        ]);
    }
}
