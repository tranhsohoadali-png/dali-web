<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/** Tài khoản seller POD (cổng /pod). Xưởng cấp tài khoản, seller không tự đăng ký. Bảng pod_seller. */
class PodSeller extends Model
{
    protected $table = 'pod_seller';
    protected $guarded = [];
    protected $hidden = ['matkhau'];
    protected $casts = [
        'hien'          => 'boolean',
        'cho_duyet'     => 'boolean',
        'dong_y_luc'    => 'datetime',
        'dang_nhap_luc' => 'datetime',
    ];

    /** Số dư ví = tổng mọi dòng sổ ví. */
    public function soDu(): int
    {
        return (int) DB::table('pod_vi')->where('seller_id', $this->id)->sum('so_tien');
    }

    /** Mã seller 6 ký tự, bỏ chữ dễ nhầm (0/O, 1/I/L) vì seller gõ tay vào nội dung chuyển khoản. */
    public static function taoMa(): string
    {
        $chu = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        do {
            $ma = '';
            for ($i = 0; $i < 6; $i++) $ma .= $chu[random_int(0, strlen($chu) - 1)];
        } while (static::where('ma', $ma)->exists());
        return $ma;
    }
}
