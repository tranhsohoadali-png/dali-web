<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Tài khoản thợ in POD: chỉ vào được /tho-in (hàng đợi in). Bảng pod_tho_in. */
class PodThoIn extends Model
{
    protected $table = 'pod_tho_in';
    protected $guarded = [];
    protected $hidden = ['matkhau'];
    protected $casts = [
        'hien'          => 'boolean',
        'dang_nhap_luc' => 'datetime',
    ];
}
