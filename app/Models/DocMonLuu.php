<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Kết quả đọc môn đã lưu theo sha256 ảnh (bảng doc_mon_luu) — đỡ gọi AI lại cho cùng một ảnh. */
class DocMonLuu extends Model
{
    protected $table = 'doc_mon_luu';
    protected $guarded = [];
    protected $casts = ['ket_qua' => 'array'];
}
