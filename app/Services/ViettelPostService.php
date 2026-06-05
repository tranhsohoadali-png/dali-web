<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Client tích hợp Viettel Post Open API (Partner v2).
 * Tài liệu: https://partner2.viettelpost.vn/document
 */
class ViettelPostService
{
    protected array $cfg;

    public function __construct()
    {
        $this->cfg = DB::table('admin_settings')->pluck('value', 'key')->toArray();
    }

    public function enabled(): bool
    {
        return ($this->cfg['vtp_enabled'] ?? '0') === '1' && !empty($this->cfg['vtp_token']);
    }

    /**
     * Lấy giá trị cấu hình, fallback sang key khác khi RỖNG (không chỉ khi null).
     * (Trước đây dùng ?? nên chuỗi rỗng '' không fallback -> gửi SENDER_* rỗng -> VTP từ chối.)
     */
    protected function val(string $key, string $fallbackKey = ''): string
    {
        $v = trim((string) ($this->cfg[$key] ?? ''));
        if ($v === '' && $fallbackKey !== '') {
            $v = trim((string) ($this->cfg[$fallbackKey] ?? ''));
        }
        return $v;
    }

    protected function baseUrl(): string
    {
        return ($this->cfg['vtp_env'] ?? 'prod') === 'dev'
            ? 'https://partnerdev.viettelpost.vn'
            : 'https://partner.viettelpost.vn';
    }

    /** Đổi token bí mật (từ viettelpost.vn) sang JWT dùng cho header Token. Cache 12h. */
    protected function jwt(bool $fresh = false): string
    {
        $secret = trim($this->cfg['vtp_token'] ?? '');
        if ($secret === '') throw new RuntimeException('Chưa cấu hình token Viettel Post trong Cài đặt.');

        $cacheKey = 'vtp_jwt_' . md5($secret . $this->baseUrl());
        if ($fresh) Cache::forget($cacheKey);

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($secret) {
            $res = Http::asJson()->acceptJson()
                ->post($this->baseUrl() . '/v2/user/LoginVTP', ['token' => $secret]);
            $json = $res->json();
            if (!$res->ok() || ($json['error'] ?? true) || empty($json['data']['token'])) {
                throw new RuntimeException('Không lấy được token VTP: ' . ($json['message'] ?? $res->status()));
            }
            return $json['data']['token'];
        });
    }

    /** Gọi 1 API có xác thực; tự refresh JWT nếu hết hạn rồi thử lại 1 lần. */
    protected function call(string $path, array $payload, bool $retry = true): array
    {
        $res = Http::withHeaders([
                'Token'        => $this->jwt(),
                'Content-Type' => 'application/json;charset=UTF-8',
            ])->acceptJson()->post($this->baseUrl() . $path, $payload);

        $json = is_array($res->json()) ? $res->json() : [];
        $msg  = is_array($json['message'] ?? null) ? json_encode($json['message']) : ($json['message'] ?? '');

        // Token hết hạn → lấy lại JWT mới rồi thử lại 1 lần
        if ($retry && stripos((string) $msg, 'token') !== false && stripos((string) $msg, 'invalid') !== false) {
            $this->jwt(true);
            return $this->call($path, $payload, false);
        }

        if (!$res->ok() || ($json['error'] ?? true)) {
            Log::warning('VTP API error', ['path' => $path, 'resp' => $json]);
            throw new RuntimeException($msg !== '' ? $msg : ('Lỗi VTP HTTP ' . $res->status()));
        }
        return $json;
    }

    /**
     * Tính cước theo địa chỉ chi tiết (NLP).
     * @return array data: MONEY_TOTAL, MONEY_VAT, KPI_HT...
     */
    public function getPrice(string $receiverAddress, int $weight, int $productPrice = 0, int $cod = 0, ?string $service = null): array
    {
        $service ??= $this->cfg['vtp_service'] ?? 'VCN';
        $payload = [
            'SENDER_ADDRESS'    => $this->val('vtp_sender_address', 'shop_address'),
            'RECEIVER_ADDRESS'  => $receiverAddress,
            'PRODUCT_TYPE'      => 'HH',
            'PRODUCT_WEIGHT'    => max(1, $weight),
            'PRODUCT_PRICE'     => $productPrice,
            'MONEY_COLLECTION'  => $cod,
            'ORDER_SERVICE'     => $service,
            'ORDER_SERVICE_ADD' => '',
            'NATIONAL_TYPE'     => 1,
            'PRODUCT_LENGTH'    => 0,
            'PRODUCT_WIDTH'     => 0,
            'PRODUCT_HEIGHT'    => 0,
        ];
        return $this->call('/v2/order/getPriceNlp', $payload)['data'] ?? [];
    }

    /**
     * Tạo vận đơn từ 1 đơn hàng DALI.
     * @return array data: ORDER_NUMBER, MONEY_TOTAL...
     */
    public function createOrder(\App\Models\Order $order, ?string $service = null): array
    {
        $service ??= $this->cfg['vtp_service'] ?? 'VCN';
        $isCod   = $order->payment_method === 'COD';
        $weight  = (int) ($order->weight ?: ($this->cfg['default_weight'] ?? 500));
        $qty     = (int) ($order->items()->sum('quantity') ?: 1);

        $payload = [
            'ORDER_NUMBER'      => $order->code,                    // mã tham chiếu phía DALI
            'SENDER_FULLNAME'   => $this->val('vtp_sender_name') ?: 'DALI',
            'SENDER_PHONE'      => $this->val('vtp_sender_phone', 'shop_phone'),
            'SENDER_ADDRESS'    => $this->val('vtp_sender_address', 'shop_address'),
            'RECEIVER_FULLNAME' => $order->customer_name,
            'RECEIVER_PHONE'    => $order->customer_phone,
            'RECEIVER_ADDRESS'  => trim($order->customer_address . ', ' . $order->customer_city, ', '),
            'PRODUCT_NAME'      => 'Tranh tô màu số hóa DALI',
            'PRODUCT_QUANTITY'  => $qty,
            'PRODUCT_PRICE'     => (int) $order->subtotal,
            'PRODUCT_WEIGHT'    => $weight,
            'PRODUCT_LENGTH'    => 0,
            'PRODUCT_WIDTH'     => 0,
            'PRODUCT_HEIGHT'    => 0,
            'PRODUCT_TYPE'      => 'HH',
            'ORDER_SERVICE'     => $service,
            'ORDER_SERVICE_ADD' => null,
            'ORDER_PAYMENT'     => $isCod ? 2 : 1,                  // 2: thu hộ tiền hàng (shop trả cước) | 1: không thu
            'MONEY_COLLECTION'  => $isCod ? (int) $order->total : 0,
            'ORDER_NOTE'        => $order->note ?: 'Cho khách xem hàng, không cho thử',
            'CHECK_UNIQUE'      => true,
        ];
        return $this->call('/v2/order/createOrderNlp', $payload)['data'] ?? [];
    }

    /** Hủy vận đơn (chỉ khi đơn chưa lấy thành công, ORDER_STATUS < 200). */
    public function cancelOrder(string $vtpNumber, string $note = 'Hủy đơn'): array
    {
        return $this->call('/v2/order/UpdateOrder', [
            'TYPE'         => 4,
            'ORDER_NUMBER' => $vtpNumber,
            'NOTE'         => $note,
        ]);
    }

    /** Lấy link in nhãn vận đơn (A6 mặc định). */
    public function printLink(string $vtpNumber, string $type = '2'): string
    {
        $res  = $this->call('/v2/order/printing-code', [
            'EXPIRY_TIME' => (now()->addDay()->timestamp) * 1000,
            'ORDER_ARRAY' => [$vtpNumber],
        ]);
        $code = $res['message'] ?? '';
        if ($code === '') throw new RuntimeException('Không lấy được mã in vận đơn.');
        $host = ($this->cfg['vtp_env'] ?? 'prod') === 'dev'
            ? 'https://dev-release-print.viettelpost.vn'
            : 'https://digitalize.viettelpost.vn';
        return "{$host}/DigitalizePrint/report.do?type={$type}&bill={$code}&showPostage=1";
    }
}
