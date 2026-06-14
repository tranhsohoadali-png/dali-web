<?php

namespace App\Providers;

use App\Models\DesignLead;
use App\Models\Order;
use App\Services\AgentPush;
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
        });
    }
}
