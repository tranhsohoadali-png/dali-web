<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code','customer_name','customer_phone','customer_city',
        'customer_address','note','coupon_code','coupon_discount',
        'affiliate_code','affiliate_commission',
        'payment_method','payment_status','status',
        'subtotal','discount','ship_fee','total',
    ];

    public function items()   { return $this->hasMany(OrderItem::class); }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'new'       => 'Đơn mới',
            'confirmed' => 'Đã xác nhận',
            'packing'   => 'Đang đóng gói',
            'shipping'  => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã huỷ',
            default     => $this->status,
        };
    }
    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'new'       => '#2563EB',
            'confirmed' => '#7C3AED',
            'packing'   => '#D97706',
            'shipping'  => '#0891B2',
            'delivered' => '#16A34A',
            'cancelled' => '#DC2626',
            default     => '#6B7280',
        };
    }
    public function getPaymentLabelAttribute(): string {
        return $this->payment_method === 'BANK' ? 'Chuyển khoản QR' : 'COD';
    }
    public function getPaymentStatusLabelAttribute(): string {
        return match($this->payment_status) {
            'paid'   => 'Đã thanh toán',
            'failed' => 'Thất bại',
            default  => 'Chờ thanh toán',
        };
    }
}
