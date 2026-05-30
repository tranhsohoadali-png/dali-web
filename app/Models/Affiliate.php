<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = [
        'name','phone','email','code','commission_rate',
        'total_earned','total_paid','total_orders',
        'bank_name','bank_acc','bank_owner','is_active','note',
    ];
    protected $casts = ['is_active' => 'boolean'];

    public function getBalanceAttribute(): int
    {
        return $this->total_earned - $this->total_paid;
    }

    public function orders()
    {
        return \App\Models\Order::where('affiliate_code', $this->code);
    }
}
