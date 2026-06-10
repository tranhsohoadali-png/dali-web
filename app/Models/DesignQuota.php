<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignQuota extends Model
{
    /** Số lượt "tạo kết quả AI" miễn phí cho mỗi device. */
    public const FREE = 3;

    /** Số lượt thưởng khi đặt hàng thành công. */
    public const ORDER_BONUS = 5;

    protected $fillable = ['device_id', 'used', 'bonus', 'last_ip'];

    /** Tổng lượt được phép = miễn phí + thưởng. */
    public function getAllowedAttribute(): int
    {
        return self::FREE + (int) $this->bonus;
    }

    /** Lượt còn lại. */
    public function getRemainingAttribute(): int
    {
        return max(0, $this->allowed - (int) $this->used);
    }
}
