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
}
