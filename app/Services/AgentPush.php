<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Đẩy dữ liệu khách (lead/đơn) realtime sang app marketing.
 * AN TOÀN TUYỆT ĐỐI cho luồng khách: bọc try/catch + timeout ngắn, KHÔNG
 * bao giờ ném lỗi ra ngoài. Nên gọi qua dispatch(...)->afterResponse() để
 * không thêm độ trễ cho khách.
 */
class AgentPush
{
    public static function send(array $payload): void
    {
        if (!config('integration.push_leads', true)) {
            return;
        }

        $url   = (string) config('integration.agent_url');
        $token = (string) config('integration.token');
        if ($url === '' || $token === '') {
            return;
        }

        try {
            Http::connectTimeout(2)->timeout(2)
                ->withHeaders(['X-Integration-Token' => $token])
                ->post($url . '/api/web-hook/lead', $payload);
        } catch (\Throwable $e) {
            Log::warning('AgentPush lỗi (bỏ qua): ' . $e->getMessage());
        }
    }
}
