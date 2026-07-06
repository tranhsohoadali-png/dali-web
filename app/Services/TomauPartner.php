<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Báo đơn (của khách đến từ CTV tomau, đơn có tomau_ref) về cổng CTV tomau để
 * trả hoa hồng chéo. Mô hình "gộp về cổng tomau": tranhdali TỰ TÍNH hoa hồng
 * (theo % config) rồi gửi sang; tomau chỉ ghi + hiển thị + trả gộp.
 *
 * AN TOÀN TUYỆT ĐỐI cho luồng khách: try/catch + timeout ngắn, KHÔNG bao giờ
 * ném lỗi. Luôn gọi qua dispatch(...)->afterResponse() để không thêm độ trễ.
 */
class TomauPartner
{
    /** Hoa hồng tomau cho 1 đơn = total * % (config, mặc định 10%). */
    public static function commissionFor(Order $order): int
    {
        $rate = (float) config('tomau.rate', 10);
        return (int) round(((int) $order->total) * $rate / 100);
    }

    /**
     * Gửi webhook báo đơn. $event ∈ 'pending' | 'paid' | 'reversed'.
     * Bỏ qua nếu tắt / thiếu cấu hình / đơn không có tomau_ref.
     */
    public static function send(Order $order, string $event): void
    {
        if (!config('tomau.enabled', true)) {
            return;
        }
        $ref = trim((string) $order->tomau_ref);
        if ($ref === '') {
            return;
        }

        $url   = (string) config('tomau.url');
        $token = (string) config('tomau.token');
        if ($url === '' || $token === '') {
            return;
        }

        // Phân loại đơn: TK-xxx = tranh thiết kế; còn lại = tranh có sẵn.
        $kind = str_starts_with((string) $order->code, 'TK-') ? 'thietke' : 'product';

        try {
            Http::connectTimeout(2)->timeout(3)
                ->withHeaders(['X-Partner-Token' => $token])
                ->post($url . '/api/partner/tranhdali-sale', [
                    'ref'          => $ref,
                    'order_code'   => (string) $order->code,
                    'order_amount' => (int) $order->total,
                    'commission'   => self::commissionFor($order),
                    'rate'         => (float) config('tomau.rate', 10),
                    'kind'         => $kind,
                    'event'        => $event,
                ]);
        } catch (\Throwable $e) {
            Log::warning('TomauPartner lỗi (bỏ qua): ' . $e->getMessage());
        }
    }
}
