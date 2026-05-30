<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'affiliate_id','amount','bank_name','bank_acc','bank_owner',
        'status','note','processed_at',
    ];
    protected $casts = ['processed_at' => 'datetime'];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Chờ duyệt',
            'approved' => 'Đã chuyển khoản',
            'rejected' => 'Đã từ chối',
            default    => $this->status,
        };
    }
}
