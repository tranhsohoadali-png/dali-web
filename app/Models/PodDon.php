<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Đơn của seller POD. Bảng pod_don. */
class PodDon extends Model
{
    protected $table = 'pod_don';
    protected $guarded = [];
    protected $hidden = ['nhan'];
    protected $casts = [
        'noi_giao' => 'array',
        'da_tru'   => 'boolean',
        'giao_luc' => 'datetime',
        'huy_luc'  => 'datetime',
    ];

    public const TRANG_THAI = [
        'cho_nap' => 'Chờ nạp tiền',
        'cho_in'  => 'Chờ in',
        'dang_in' => 'Đang in',
        'da_giao' => 'Đã giao',
        'da_huy'  => 'Đã huỷ',
    ];

    public function dong()
    {
        return $this->hasMany(PodDonDong::class, 'don_id')->orderBy('id');
    }

    public function seller()
    {
        return $this->belongsTo(PodSeller::class, 'seller_id');
    }

    public function tenTrangThai(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? $this->trang_thai;
    }

    public static function taoMa(): string
    {
        do {
            $ma = 'PD' . now()->format('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        } while (static::where('ma', $ma)->exists());
        return $ma;
    }
}
