<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Đơn hàng khu Xưởng in 3D. Bảng riêng don_3d. */
class Don3d extends Model
{
    protected $table = 'don_3d';

    protected $guarded = [];

    protected $casts = [
        'chi_tiet' => 'array',
        'xong_luc' => 'datetime',
    ];
}
