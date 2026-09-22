<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Đơn "đi đơn hộ" của đại lý (bán trên sàn TMĐT). Bảng riêng don_di_ho. */
class DonDiHo extends Model
{
    protected $table = 'don_di_ho';

    protected $guarded = [];

    protected $casts = [
        'chi_tiet'       => 'array',
        'mon_soan'       => 'array',
        'gui_luc'        => 'datetime',
        'da_thanh_toan'   => 'boolean',
        'thanh_toan_luc'  => 'datetime',
        'dai_ly_xac_nhan' => 'boolean',
        'xac_nhan_luc'    => 'datetime',
        'thu_them'        => 'integer',
    ];

    /** Nhãn trạng thái tiếng Việt cho quản trị. */
    public const TRANG_THAI = [
        'moi'    => 'Mới',
        'da_in'  => 'Đã in',
        'da_goi' => 'Đã đóng gói',
        'da_gui' => 'Đã gửi',       // đại lý tự cập nhật
        'huy'    => 'Huỷ',
    ];
}
