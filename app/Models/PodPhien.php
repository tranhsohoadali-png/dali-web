<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Một phiên đăng nhập (một thiết bị) của seller POD. Chỉ lưu băm SHA-256 của token. Bảng pod_phien. */
class PodPhien extends Model
{
    protected $table = 'pod_phien';
    protected $guarded = [];
    protected $hidden = ['token_hash'];
    protected $casts = ['dung_luc' => 'datetime'];

    public const HET_HAN_NGAY = 90;
    public const TOI_DA       = 10;

    public static function bam(string $token): string
    {
        return hash('sha256', $token);
    }

    /** Nhãn dễ đọc cho chuỗi trình duyệt: "Chrome · Windows", "Zalo · iPhone"… (trang Thiết bị đăng nhập). */
    public static function nhanThietBi(?string $ua): string
    {
        $ua = (string) $ua;
        if ($ua === '') return 'Không rõ thiết bị';
        $tb = match (true) {
            str_contains($ua, 'Zalo')                                   => 'Zalo',
            (bool) preg_match('/FBAN|FBAV|FB_IAB/', $ua)                => 'Facebook',
            str_contains($ua, 'Edg/')                                   => 'Edge',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera')     => 'Opera',
            str_contains($ua, 'CocCoc') || str_contains($ua, 'coc_coc') => 'Cốc Cốc',
            str_contains($ua, 'Firefox/')                               => 'Firefox',
            str_contains($ua, 'Chrome/') || str_contains($ua, 'CriOS') => 'Chrome',
            str_contains($ua, 'Safari/')                                => 'Safari',
            default                                                     => 'Trình duyệt',
        };
        $hdh = match (true) {
            str_contains($ua, 'iPhone')                                     => 'iPhone',
            str_contains($ua, 'iPad')                                       => 'iPad',
            str_contains($ua, 'Android')                                    => 'Android',
            str_contains($ua, 'Windows')                                    => 'Windows',
            str_contains($ua, 'Mac OS X') || str_contains($ua, 'Macintosh') => 'Mac',
            str_contains($ua, 'Linux')                                      => 'Linux',
            default                                                         => '',
        };
        return $tb . ($hdh !== '' ? ' · ' . $hdh : '');
    }
}
