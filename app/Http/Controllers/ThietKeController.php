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

    public function index(Request $r)
    {
        // Khách vào qua link CTV (?ref=CODE) -> lưu mã 30 ngày để gắn hoa hồng khi đặt.
        $refCode = strtoupper(trim($r->input('ref', '')));
        if ($refCode) {
            $aff = \App\Models\Affiliate::where('code', $refCode)->where('is_active', true)->first();
            if ($aff) {
                session(['affiliate_code' => $refCode]);
                cookie()->queue('affiliate_code', $refCode, 60 * 24 * 30);
            }
        }
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
                    // Khách web gửi ảnh CHÂN DUNG/ẢNH THẬT -> mặc định 'photo'
                    // (giữ đúng người thật 30-48 màu), không phải 'anime' hoạt hình.
                    'preset'      => $r->input('preset', 'photo'),
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

    /**
     * Sao lưu VĨNH VIỄN 3 ảnh của đơn về server bán hàng (ảnh server màu tự xoá
     * sau 24h). Trả về mảng URL local đã lưu; lỗi/ảnh không tải được -> giữ URL gốc.
     */
    private function backupOrderImages(string $code, array $urls): array
    {
        $out = $urls;
        $dir = public_path('images/tk-orders/' . $code);
        foreach ($urls as $key => $url) {
            if (!$url || !preg_match('#^https?://#', $url)) continue;
            try {
                $resp = Http::timeout(15)->get($url);
                if (!$resp->ok() || strlen($resp->body()) < 500) continue;   // ảnh đã bị xoá/hỏng
                $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $ext = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';
                if (!is_dir($dir)) @mkdir($dir, 0775, true);
                $file = $dir . '/' . $key . '.' . $ext;
                file_put_contents($file, $resp->body());
                $out[$key] = url('images/tk-orders/' . $code . '/' . $key . '.' . $ext);
            } catch (\Throwable $e) {
                // giữ URL gốc nếu tải lỗi — không làm hỏng đơn
            }
        }
        return $out;
    }

    /** Lưu SĐT khách kèm bản thiết kế (hiện trong Admin -> Khách thiết kế). */
    public function lead(Request $r)
    {
        $phone = preg_replace('/[\s.\-]/', '', (string) $r->input('phone', ''));
        if (!preg_match('/^(0|\+?84)(3|5|7|8|9)\d{8}$/', $phone)) {
            return response()->json(['ok' => false, 'msg' => 'Số điện thoại không hợp lệ.'], 422);
        }
        \App\Models\DesignLead::create([
            'phone'        => $phone,
            'device_id'    => $this->deviceId($r),
            'original_url' => substr((string) $r->input('original_url', ''), 0, 1000),
            'enhanced_url' => substr((string) $r->input('enhanced_url', ''), 0, 1000),
            'result_url'   => substr((string) $r->input('result_url', ''), 0, 1000),
        ]);
        return response()->json(['ok' => true]);
    }

    /** Đặt hàng từ trang thiết kế -> tạo đơn + cộng ORDER_BONUS lượt cho device. */
    public function order(Request $r)
    {
        $data = $r->validate([
            'customer_name'  => 'required|string|max:120',
            'customer_phone' => 'required|string|max:30',
        ]);
        $did = $this->deviceId($r);

        // SĐT Việt Nam — client có validate nhưng POST thẳng thì không:
        // bỏ khoảng trắng/chấm/gạch rồi kiểm tra phía server.
        $phone = preg_replace('/[\s.\-]+/', '', $data['customer_phone']);
        if (!preg_match('/^(0|\+?84)(3|5|7|8|9)\d{8}$/', $phone)) {
            return response()->json(['ok' => false, 'msg' => 'Số điện thoại không hợp lệ'], 422);
        }

        // Chống đúp đơn khi retry/bypass nút gửi — phải chặn TRƯỚC khi cộng
        // bonus, vì mỗi đơn đúp lại +ORDER_BONUS lượt. Cùng SĐT đã có đơn
        // thiết kế trong 5 phút -> trả lại mã đơn cũ, không cộng lượt nữa.
        $dup = Order::where('customer_phone', $phone)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->where('note', 'like', 'ĐƠN THIẾT KẾ THEO YÊU CẦU%')
            ->latest('id')
            ->first();
        if ($dup) {
            return response()->json([
                'ok' => true, 'code' => $dup->code, 'duplicate' => true,
                'bonus' => 0, 'remaining' => $did ? $this->quotaFor($did)->remaining : 0,
            ]);
        }
        // cache->add nguyên tử (theo device+SĐT): chặn nốt 2 POST song song
        // cùng lọt qua truy vấn trên.
        $lock = 'tk_order_' . md5($did . '|' . $phone);
        if (!cache()->add($lock, 1, 300)) {
            return response()->json(['ok' => false, 'msg' => 'Đơn của bạn đang được xử lý, vui lòng đợi giây lát.'], 429);
        }

        // Giá tranh khách chọn + tiền cọc 20% (client gửi; chặn số âm/quá lớn).
        $price   = max(0, min((int) $r->input('price', 0), 100000000));
        $deposit = max(0, min((int) $r->input('deposit', 0), $price));

        // Mã CTV: ưu tiên client gửi -> session -> cookie (link /ref/<mã> đã lưu 30 ngày)
        $affCode = strtoupper(trim(
            $r->input('affiliate_code', '')
            ?: session('affiliate_code', '')
            ?: $r->cookie('affiliate_code', '')
        ));

        $code = 'TK-' . strtoupper(substr(uniqid(), -6));

        // Hàm dựng ghi chú từ bộ URL ảnh (gốc/AI/bản đồ màu).
        $pkg = $r->input('package');
        $buildNote = fn(array $u) => 'ĐƠN THIẾT KẾ THEO YÊU CẦU'
            . ($pkg ? ' — Gói: ' . $pkg : '')
            . ($u['goc'] ? '. Ảnh gốc: ' . $u['goc'] : '')
            . ' | Bản đồ màu: ' . ($u['map'] ?: '(chưa có)')
            . ($u['ai'] ? ' | Ảnh AI: ' . $u['ai'] : '');

        // Lúc tạo đơn dùng URL gốc (trên server màu) -> đơn tạo NHANH, khách không chờ.
        $src = [
            'goc' => (string) $r->input('original_url', ''),
            'ai'  => (string) $r->input('enhanced_url', ''),
            'map' => (string) $r->input('result_url', ''),
        ];

        try {
            $order = Order::create([
                'code'             => $code,
                'customer_name'    => $data['customer_name'],
                'customer_phone'   => $phone,
                'customer_city'    => (string) $r->input('customer_city', ''),
                'customer_address' => (string) $r->input('customer_address', ''),
                'note'             => $buildNote($src),
                'status'           => 'new',
                'payment_method'   => 'COD',
                'payment_status'   => 'pending',
                'subtotal'         => $price,
                'ship_fee'         => 0,
                'total'            => $price,
                'deposit'          => $deposit,
            ]);
        } catch (\Throwable $e) {
            cache()->forget($lock); // tạo đơn lỗi -> nhả khoá để khách gửi lại ngay
            throw $e;
        }

        // Ghi hoa hồng CTV trên GIÁ TRANH khách chọn (giống đơn bán thường).
        if ($affCode && $price > 0) {
            $aff = \App\Models\Affiliate::where('code', $affCode)->where('is_active', true)->first();
            if ($aff) {
                $commission = (int) round($price * $aff->commission_rate / 100);
                $order->update(['affiliate_code' => $affCode, 'affiliate_commission' => $commission]);
                $aff->increment('total_earned', $commission);
                $aff->increment('total_orders');
            }
        }

        $remaining = 0;
        if ($did) {
            $q = $this->quotaFor($did);
            $q->increment('bonus', DesignQuota::ORDER_BONUS);
            $q->refresh();
            $remaining = $q->remaining;
        }

        // Sao lưu 3 ảnh về server bán hàng SAU KHI đã trả kết quả cho khách
        // (defer chạy sau response) -> khách không phải chờ tải ảnh; ảnh server
        // màu xoá sau 24h nhưng đơn đã đặt vẫn giữ ảnh vĩnh viễn để in tranh.
        defer(function () use ($order, $code, $src, $buildNote) {
            $imgs = $this->backupOrderImages($code, $src);
            if ($imgs !== $src) {
                $order->update(['note' => $buildNote($imgs)]);
            }
        });

        return response()->json([
            'ok' => true, 'code' => $code,
            'bonus' => DesignQuota::ORDER_BONUS, 'remaining' => $remaining,
        ]);
    }
}
