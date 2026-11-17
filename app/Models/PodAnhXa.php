<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Nhập đơn từ file: tên sản phẩm trong file của seller -> phân loại của xưởng (nhớ cho lần sau). Bảng pod_anh_xa. */
class PodAnhXa extends Model
{
    protected $table = 'pod_anh_xa';
    protected $guarded = [];
}
