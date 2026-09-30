<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Một phiên đăng nhập (một thiết bị) của đại lý. Chỉ lưu băm SHA-256 của token. Bảng dai_ly_phien. */
class DaiLyPhien extends Model
{
    protected $table = 'dai_ly_phien';
    protected $guarded = [];
    protected $hidden = ['token_hash'];
    protected $casts = ['dung_luc' => 'datetime'];

    public const HET_HAN_NGAY = 90; // không dùng quá 90 ngày thì tự hết hạn
    public const TOI_DA       = 10; // giữ tối đa 10 thiết bị / đại lý (bỏ phiên cũ nhất)

    public static function bam(string $token): string
    {
        return hash('sha256', $token);
    }
}
