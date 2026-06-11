<?php

namespace App\Http\Controllers;

use App\Models\DesignQuota;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Trang "Thiết kế theo yêu cầu" (/thiet-ke):
 * khách upload ảnh -> gọi API phần mềm màu (mau.tranhdali.vn) tăng cường AI +
 * bản đồ màu. Mỗi device được FREE lượt; đặt hàng thành công +ORDER_BONUS lượt.
 */
class ThietKeController extends Controller
{
    private function settings()
    {
        return DB::table('admin_settings')->pluck('value', 'key');
    }

    private function deviceId(Request $r): string
    {
        $d = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $r->input('device_id', ''));
        return substr($d, 0, 64);
    }

    private function quotaFor(string $deviceId): DesignQuota
    {
        return DesignQuota::firstOrCreate(['device_id' => $deviceId]);
    }

    /** IP nằm trong danh sách máy test (Cài đặt) -> không giới hạn lượt. */
    private function isTestIp(Request $r): bool
    {
        $raw = (string) ($this->settings()['thietke_test_ips'] ?? '');
        if ($raw === '') return false;
        $ips = preg_split('/[\s,;]+/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        return in_array($r->ip(), $ips, true);
    }

    public function index()
    {
        $settings = $this->settings();
        $pricing  = \App\Http\Controllers\Admin\ThietKePricingController::current();
        return view('website.thiet-ke', compact('settings', 'pricing'));
    }

    /** Trả số lượt còn lại của device. */
    public function quota(Request $r)
    {
        if ($this->isTestIp($r)) {
            return response()->json(['remaining' => 9999, 'free' => DesignQuota::FREE, 'unlimited' => true]);
        }
        $did = $this->deviceId($r);
        if (!$did) {
            return response()->json(['remaining' => DesignQuota::FREE, 'free' => DesignQuota::FREE]);
        }
        $q = $this->quotaFor($did);
        return response()->json([
            'remaining' => $q->remaining, 'free' => DesignQuota::FREE,
            'allowed'   => $q->allowed,   'used' => (int) $q->used,
        ]);
    }

    /** Tạo kết quả: kiểm quota -> gọi API màu -> trừ 1 lượt. */
    public function generate(Request $r)
    {
        $did = $this->deviceId($r);
        if (!$did) {
            return response()->json(['ok' => false, 'msg' => 'Thiếu mã thiết bị.'], 400);
        }
        if (!$r->hasFile('image')) {
            return response()->json(['ok' => false, 'msg' => 'Vui lòng chọn ảnh.'], 400);
        }

        $s   = $this->settings();
        // URL tự mặc định -> admin chỉ cần nhập KHOÁ là đủ.
        $url = trim($s['thietke_api_url'] ?? '') ?: 'https://mau.tranhdali.vn/api/xu-ly-anh';
        $key = trim($s['thietke_api_key'] ?? '');
        if (!$key) {
            return response()->json(['ok' => false, 'msg' => 'Tính năng chưa được cấu hình. Vui lòng liên hệ shop.'], 503);
        }

        $isTest = $this->isTestIp($r);   // máy test: không giới hạn, không trừ lượt
        $q = $this->quotaFor($did);
        if (!$isTest && $q->remaining <= 0) {
            return response()->json([
                'ok' => false, 'reason' => 'no_quota', 'remaining' => 0,
                'msg' => 'Bạn đã hết lượt tạo miễn phí. Đặt hàng để nhận thêm ' . DesignQuota::ORDER_BONUS . ' lượt.',
            ], 403);
        }

        $file = $r->file('image');
        try {
            // BẤT ĐỒNG BỘ: chỉ gửi ảnh + nhận id job (1-2s) -> không dính
            // timeout proxy/nginx 60s. Kết quả lấy qua /thiet-ke/trang-thai.
            $resp = Http::timeout(60)
                ->withHeaders(['X-API-Key' => $key])
                ->attach('image', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post($url, [
                    'enhance'     => $r->input('enhance', '1'),
                    'preset'      => $r->input('preset', 'anime'),
                    'print_size'  => $r->input('print_size', '40x50'),
                    'color_limit' => (int) $r->input('color_limit', 0),
                ]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'msg' => 'Không kết nối được hệ thống xử lý ảnh.'], 502);
        }

        $data = is_array($resp->json()) ? $resp->json() : [];
        if (!$resp->ok() || !($data['ok'] ?? false) || empty($data['id'])) {
            return response()->json(['ok' => false, 'msg' => $data['error'] ?? ('Lỗi xử lý ảnh (' . $resp->status() . ').')], 502);
        }

        // Job đã khởi động -> trừ 1 lượt (máy test thì không trừ);
        // ghi nhớ job-device để hoàn lượt nếu lỗi.
        if (!$isTest) {
            $q->increment('used');
            cache()->put('tk_job_' . $data['id'], $did, now()->addHours(2));
        }
        $q->update(['last_ip' => $r->ip()]);
        $q->refresh();

        return response()->json([
            'ok' => true, 'job' => $data['id'],
            'remaining' => $isTest ? 9999 : $q->remaining,
            'unlimited' => $isTest,
        ]);
    }

    /** Poll trạng thái job (proxy sang phần mềm màu, kèm khoá phía server). */
    public function status(Request $r)
    {
        $s   = $this->settings();
        $url = trim($s['thietke_api_url'] ?? '') ?: 'https://mau.tranhdali.vn/api/xu-ly-anh';
        $key = trim($s['thietke_api_key'] ?? '');
        $job = (int) $r->input('job', 0);
        if (!$key || !$job) {
            return response()->json(['ok' => false, 'status' => 'error', 'msg' => 'Thiếu cấu hình hoặc job.'], 400);
        }
        try {
            $resp = Http::timeout(20)->withHeaders(['X-API-Key' => $key])
                ->get(preg_replace('/\/?$/', '', $url) . '-trang-thai', ['id' => $job]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => true, 'status' => 'processing']); // lỗi mạng tạm -> poll tiếp
        }
        $data = is_array($resp->json()) ? $resp->json() : [];

        // 401 = khoá API 2 hệ thống không khớp -> báo rõ (không phải lỗi job, không hoàn lượt nhầm)
        if ($resp->status() === 401) {
            return response()->json(['ok' => true, 'status' => 'error',
                'msg' => 'Khoá API giữa website và hệ thống màu KHÔNG khớp — vào Admin → Cài đặt kiểm tra lại khoá 2 bên.']);
        }
        // Không phải JSON job (vd 502 HTML khi hệ thống màu đang khởi động lại)
        // -> coi là tạm thời, poll tiếp; KHÔNG kết luận job lỗi.
        $st = $data['status'] ?? 'processing';

        if ($st === 'error') {
            // Job hỏng -> HOÀN 1 lượt cho device (chỉ 1 lần / job)
            $did = cache()->pull('tk_job_' . $job);
            $remaining = null;
            if ($did) {
                $q = $this->quotaFor($did);
                if ($q->used > 0) $q->decrement('used');
                $q->refresh();
                $remaining = $q->remaining;
            }
            return response()->json(['ok' => true, 'status' => 'error',
                'msg' => $data['error'] ?? 'Xử lý thất bại.', 'remaining' => $remaining]);
        }
        if ($st === 'done') {
            cache()->forget('tk_job_' . $job);
            return response()->json(['ok' => true, 'status' => 'done', 'result' => $data]);
        }
        return response()->json(['ok' => true, 'status' => 'processing']);
    }

    /** Đặt hàng từ trang thiết kế -> tạo đơn + cộng ORDER_BONUS lượt cho device. */
    public function order(Request $r)
    {
        $data = $r->validate([
            'customer_name'  => 'required|string|max:120',
            'customer_phone' => 'required|string|max:30',
        ]);
        $did = $this->deviceId($r);

        $code  = 'TK-' . strtoupper(substr(uniqid(), -6));
        $order = Order::create([
            'code'             => $code,
            'customer_name'    => $data['customer_name'],
            'customer_phone'   => $data['customer_phone'],
            'customer_city'    => $r->input('customer_city', ''),
            'customer_address' => $r->input('customer_address', ''),
            'note'             => 'ĐƠN THIẾT KẾ THEO YÊU CẦU'
                                  . ($r->input('package') ? ' — Gói: ' . $r->input('package') : '')
                                  . '. Bản đồ màu: ' . $r->input('result_url', '(chưa có)')
                                  . ($r->input('enhanced_url') ? ' | Ảnh AI: ' . $r->input('enhanced_url') : ''),
            'status'           => 'new',
            'payment_method'   => 'COD',
            'payment_status'   => 'pending',
            'subtotal'         => 0,
            'ship_fee'         => 0,
            'total'            => 0,
        ]);

        $remaining = 0;
        if ($did) {
            $q = $this->quotaFor($did);
            $q->increment('bonus', DesignQuota::ORDER_BONUS);
            $q->refresh();
            $remaining = $q->remaining;
        }

        return response()->json([
            'ok' => true, 'code' => $code,
            'bonus' => DesignQuota::ORDER_BONUS, 'remaining' => $remaining,
        ]);
    }
}
