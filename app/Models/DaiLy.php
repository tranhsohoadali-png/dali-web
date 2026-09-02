<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Tài khoản đại lý — đăng nhập trên web 3D để xem giá sỉ. Bảng dai_ly. */
class DaiLy extends Model
{
    protected $table = 'dai_ly';
    protected $guarded = [];
    protected $hidden = ['matkhau', 'token'];
    protected $casts = [
        'hien'          => 'boolean',
        'dang_nhap_luc' => 'datetime',
    ];
}
