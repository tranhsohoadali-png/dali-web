<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Danh mục con, thuộc 1 Nhóm. Bảng `danh_muc_3d`. */
class DanhMuc3d extends Model
{
    protected $table = 'danh_muc_3d';

    protected $fillable = ['nhom_id', 'ten', 'slug', 'icon', 'thu_tu', 'hien'];

    protected $casts = ['hien' => 'boolean'];

    public function nhom()
    {
        return $this->belongsTo(Nhom3d::class, 'nhom_id');
    }

    public function sanPham()
    {
        return $this->hasMany(Sp3d::class, 'danh_muc_id');
    }
}
