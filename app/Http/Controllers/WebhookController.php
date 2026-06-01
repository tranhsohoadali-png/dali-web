<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Nhận callback hành trình đơn hàng từ Viettel Post.
     * Yêu cầu: luôn trả HTTP 200 (kể cả khi bỏ qua) để VTP không retry.
     */
    public function viettelpost(Request $request)
    {
        try {
            $data  = $request->input('DATA', []);
            $token = (string) $request->input('TOKEN', '');

            // Xác thực nguồn webhook bằng secret đã cấu hình
            $secret = (string) DB::table('admin_settings')->where('key', 'vtp_webhook_token')->value('value');
            if ($secret !== '' && !hash_equals($secret, $token)) {
                Log::warning('VTP webhook: sai TOKEN xác thực');
                return response()->json(['ok' => true], 200); // vẫn 200 để không bị retry
            }

            $ref     = $data['ORDER_REFERENCE'] ?? null;   // mã đơn DALI
            $vtpNum  = $data['ORDER_NUMBER']    ?? null;   // mã vận đơn VTP
            $code    = isset($data['ORDER_STATUS']) ? (int) $data['ORDER_STATUS'] : null;

            $order = null;
            if ($ref)    $order = Order::where('code', $ref)->first();
            if (!$order && $vtpNum) $order = Order::where('vtp_order_number', $vtpNum)->first();

            if ($order && $code !== null) {
                $order->vtp_status      = $code;
                $order->vtp_status_name = $data['STATUS_NAME'] ?? $order->vtp_status_name;
                $order->vtp_status_at   = now();
                if ($vtpNum && !$order->vtp_order_number) $order->vtp_order_number = $vtpNum;

                // Đồng bộ trạng thái nội bộ (không tự ý ghi đè đơn đã hủy thủ công)
                $mapped = Order::mapVtpStatus($code);
                if ($mapped && $order->status !== 'cancelled') {
                    $order->status = $mapped;
                    if ($code === 501) $order->payment_status = 'paid'; // giao thành công
                }
                $order->save();
                Log::info("VTP webhook: đơn {$order->code} → {$code} ({$order->vtp_status_name})");
            } else {
                Log::info('VTP webhook: không khớp đơn', ['ref' => $ref, 'vtp' => $vtpNum, 'status' => $code]);
            }
        } catch (\Throwable $e) {
            Log::error('VTP webhook error: ' . $e->getMessage());
        }

        return response()->json(['ok' => true], 200);
    }
}
