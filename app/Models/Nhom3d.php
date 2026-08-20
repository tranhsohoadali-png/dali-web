<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Nhóm lớn của Xưởng in 3D (cấp trên của Danh mục). Bảng `nhom_3d`. */
class Nhom3d extends Model
{
    protected $table = 'nhom_3d';

    protected $fillable = ['ten', 'slug', 'mo_ta', 'thu_tu', 'hien'];

    protected $casts = ['hien' => 'boolean'];

    public function danhMuc()
    {
        return $this->hasMany(DanhMuc3d::class, 'nhom_id')->orderBy('thu_tu')->orderBy('ten');
    }
}
