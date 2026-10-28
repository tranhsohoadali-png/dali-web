<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Mẫu POD. chu_seller_id NULL = Kho chung (mọi seller thấy), có id = Kho riêng của seller đó.
 * MỌI truy vấn phía seller phải đi qua scopeHienCho() — đó là bộ lọc duy nhất giữ kín mẫu độc quyền.
 */
class PodMau extends Model
{
    protected $table = 'pod_mau';
    protected $guarded = [];
    protected $casts = [
        'anh'  => 'array',
        'hien' => 'boolean',
    ];

    /** Mẫu seller này được thấy: Kho chung + Kho riêng của chính seller, chỉ mẫu đang hiện. */
    public function scopeHienCho(Builder $q, int $sellerId): Builder
    {
        return $q->where('hien', true)->where(function ($w) use ($sellerId) {
            $w->whereNull('chu_seller_id')->orWhere('chu_seller_id', $sellerId);
        });
    }

    public function bienThe()
    {
        return $this->hasMany(PodBienThe::class, 'mau_id')->orderBy('thu_tu')->orderBy('id');
    }

    public function chu()
    {
        return $this->belongsTo(PodSeller::class, 'chu_seller_id');
    }

    public function laRieng(): bool
    {
        return $this->chu_seller_id !== null;
    }

    /** Mã 10 ký tự ngẫu nhiên. */
    public static function taoMa(): string
    {
        $chu = 'abcdefghjkmnpqrstuvwxyz23456789';
        do {
            $ma = '';
            for ($i = 0; $i < 10; $i++) $ma .= $chu[random_int(0, strlen($chu) - 1)];
        } while (static::where('ma', $ma)->exists());
        return $ma;
    }
}
