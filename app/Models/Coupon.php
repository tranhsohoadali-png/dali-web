<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = ['code','type','value','min_order','max_uses','used_count','expires_at','is_active','description'];
    protected $casts    = ['is_active'=>'boolean','expires_at'=>'date'];

    public function isValid(int $orderAmount = 0): array
    {
        if (!$this->is_active)                         return [false, 'Mã giảm giá đã bị vô hiệu hoá'];
        if ($this->expires_at && $this->expires_at->isPast()) return [false, 'Mã giảm giá đã hết hạn'];
        if ($this->max_uses && $this->used_count >= $this->max_uses) return [false, 'Mã giảm giá đã hết lượt sử dụng'];
        if ($orderAmount < $this->min_order)           return [false, 'Đơn hàng chưa đạt mức tối thiểu '.number_format($this->min_order,0,',','.').'đ'];
        return [true, 'OK'];
    }

    public function calcDiscount(int $amount): int
    {
        if ($this->type === 'percent') return (int) round($amount * $this->value / 100);
        return min($this->value, $amount);
    }

    public function getLabelAttribute(): string
    {
        return $this->type === 'percent' ? "Giảm {$this->value}%" : "Giảm ".number_format($this->value,0,',','.')."đ";
    }
}
