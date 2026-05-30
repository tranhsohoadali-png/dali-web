<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = [
        'name','phone','email','password','code','commission_rate',
        'total_earned','total_paid','total_orders',
        'bank_name','bank_acc','bank_owner','is_active','note',
    ];
    protected $casts = ['is_active' => 'boolean'];
    protected $hidden = ['password'];

    // Số dư = đã kiếm - đã trả
    public function getBalanceAttribute(): int
    {
        return (int) $this->total_earned - (int) $this->total_paid;
    }

    // Số tiền đang chờ duyệt rút
    public function getPendingWithdrawAttribute(): int
    {
        return (int) $this->withdrawals()->where('status', 'pending')->sum('amount');
    }

    // Số dư khả dụng (có thể rút) = số dư - đang chờ duyệt
    public function getAvailableAttribute(): int
    {
        return $this->balance - $this->pending_withdraw;
    }

    public function orders()
    {
        return \App\Models\Order::where('affiliate_code', $this->code);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->latest();
    }
}
