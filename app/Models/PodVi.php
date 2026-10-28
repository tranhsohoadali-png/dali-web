<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Một dòng sổ ví seller POD. CHỈ THÊM, không sửa/xoá: sai thì thêm dòng điều chỉnh. Bảng pod_vi. */
class PodVi extends Model
{
    protected $table = 'pod_vi';
    protected $guarded = [];

    public const LOAI = [
        'nap'        => 'Nạp tiền',
        'tru'        => 'Trừ tiền đơn',
        'hoan'       => 'Hoàn tiền',
        'dieu_chinh' => 'Điều chỉnh',
    ];
}
