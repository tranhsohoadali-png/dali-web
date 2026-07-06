<?php

namespace App\Providers;

use App\Models\DesignLead;
use App\Models\Order;
use App\Services\AgentPush;
use App\Services\TomauPartner;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đẩy lead/đơn realtime sang app marketing để remarketing.
        // Chạy SAU khi đã trả phản hồi cho khách -> không thêm độ trễ, không
        // bao giờ làm hỏng luồng đặt hàng (AgentPush tự nuốt mọi lỗi).
        DesignLead::created(function (DesignLead $lead) {
            $payload = [
                'source'     => 'design',
                'phone'      => $lead->phone,
                'created_at' => optional($lead->created_at)->toIso8601String(),
            ];
            dispatch(fn () => AgentPush::send($payload))->afterResponse();
        });

        Order::created(function (Order $order) {
            $payload = [
                'source'     => 'order',
                'code'       => $order->code,
                'name'       => $order->customer_name,
                'phone'      => $order->customer_phone,
                'city'       => $order->customer_city,
                'address'    => $order->customer_address,
                'total'      => (int) $order->total,
                'status'     => $order->status,
                'created_at' => optional($order->created_at)->toIso8601String(),
            ];
            dispatch(fn () => AgentPush::send($payload))->afterResponse();

            // Khách đến từ CTV tomau -> báo về cổng tomau (hiển thị "đơn chờ";
            // CHƯA cộng tiền — chỉ khi 'paid' mới tính, theo quyết định chủ site).
            // Đề phòng đơn tạo ra đã 'paid' ngay -> báo luôn 'paid'.
            if (trim((string) $order->tomau_ref) !== '') {
                $ev = $order->payment_status === 'paid' ? 'paid' : 'pending';
                dispatch(fn () => TomauPartner::send($order, $ev))->afterResponse();
            }
        });

        // Báo tomau khi đơn ĐÃ THANH TOÁN (paid) hoặc HUỶ (cancelled). Dùng model
        // event nên bắt được MỌI đường: admin xác nhận BANK, webhook VTP giao
        // thành công, huỷ VTP, huỷ tay… mà không phải sửa từng controller.
        // Chỉ đơn có tomau_ref mới xử lý (đơn thường bỏ qua ngay).
        Order::updated(function (Order $order) {
            if (trim((string) $order->tomau_ref) === '') {
                return;
            }
            if ($order->wasChanged('payment_status') && $order->payment_status === 'paid') {
                dispatch(fn () => TomauPartner::send($order, 'paid'))->afterResponse();
            } elseif ($order->wasChanged('status') && $order->status === 'cancelled') {
                dispatch(fn () => TomauPartner::send($order, 'reversed'))->afterResponse();
            }
        });

        // Xoá đơn -> đảo hoa hồng bên tomau (idempotent phía tomau).
        Order::deleted(function (Order $order) {
            if (trim((string) $order->tomau_ref) !== '') {
                dispatch(fn () => TomauPartner::send($order, 'reversed'))->afterResponse();
            }
        });
    }
}
