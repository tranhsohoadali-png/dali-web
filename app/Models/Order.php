<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code','customer_name','customer_phone','customer_city',
        'customer_address','note','coupon_code','coupon_discount',
        'affiliate_code','affiliate_commission','commission_reversed','tomau_ref',
        'payment_method','payment_status','status','design_status',
        'subtotal','discount','ship_fee','total','deposit','deposit_paid',
        'vtp_order_number','vtp_status','vtp_status_name','vtp_status_at','vtp_service','weight',
    ];

    protected $casts = [
        'vtp_status_at'       => 'datetime',
        'commission_reversed' => 'boolean',
    ];

    public function items()   { return $this->hasMany(OrderItem::class); }

    /** Map mã trạng thái Viettel Post → trạng thái nội bộ DALI. */
    public static function mapVtpStatus(int $code): ?string
    {
        return match (true) {
            in_array($code, [101,107,201,503])      => 'cancelled',   // hủy / tiêu hủy
            $code === 501                           => 'delivered',   // giao thành công
            $code === 504                           => 'cancelled',   // hoàn về người gửi
            $code >= 500                            => 'shipping',    // bưu tá đi phát / phát lại
            $code >= 300                            => 'shipping',    // khai thác / vận chuyển
            $code === 200                           => 'shipping',    // lấy hàng thành công
            $code >= 102                            => 'packing',     // điều phối lấy hàng
            default                                 => null,
        };
    }

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
    public function getDesignStatusLabelAttribute(): ?string {
        return match($this->design_status) {
            'pending'   => 'Chờ thiết kế',
            'delivered' => 'Đã gửi khách',
            default     => null,
        };
    }
}
