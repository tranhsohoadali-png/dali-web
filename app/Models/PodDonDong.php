<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Một dòng của đơn POD (giá chốt lúc đặt). Bảng pod_don_dong. */
class PodDonDong extends Model
{
    protected $table = 'pod_don_dong';
    protected $guarded = [];
    protected $casts = ['gram' => 'float'];
}
