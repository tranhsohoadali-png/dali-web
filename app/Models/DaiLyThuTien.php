<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Sổ thu tiền đại lý (thu từng phần). Bảng dai_ly_thu_tien. */
class DaiLyThuTien extends Model
{
    protected $table = 'dai_ly_thu_tien';
    protected $guarded = [];
    protected $casts = ['don_ids' => 'array', 'huy' => 'boolean'];
}
