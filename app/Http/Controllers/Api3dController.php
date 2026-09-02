<?php
namespace App\Http\Controllers;

use App\Models\Sp3d;
use App\Models\Don3d;
use App\Models\DaiLy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

/**
 * API công khai cho web bán hàng 3d.tranhdali.vn.
 * Chép nguyên cỗ máy checkout từ PocketBase (pb_hooks/dali_checkout.pb.js):
 * giá chỉ được tính Ở ĐÂY, không bao giờ tin số từ trình duyệt gửi lên.
 * Logic tính tiền gom vào priceCart() — dùng chung cho quote và checkout để
 * hai bên không bao giờ lệch nhau.
 */
class Api3dController extends Controller
{
    private const ALLOWED_ORIGIN = 'https://3d.tranhdali.vn';

    /* ============ GET /api/3d/catalog ============ */
    public function catalog(Request $request)
    {
        $daiLy = $this->daiLyTuRequest($request); // đại lý đăng nhập -> kèm giá sỉ
        $items = Sp3d::where('hien', true)->with('danhMuc.nhom')->orderBy('thu_tu')->orderBy('ten')->get()
            ->map(function (Sp3d $p) use ($daiLy) {
                $dm = $p->danhMuc;
                $nh = $dm?->nhom;
                return [
                    'id'             => (string) $p->id,
                    'slug'           => $p->slug,
                    'ten'            => $p->ten,
                    'art'            => $p->art,
                    'cat'            => $p->cat,
                    // Cây danh mục: Nhóm -> Danh mục (front-end chia khu theo Nhóm)
                    'nhom'           => ($nh && $nh->hien) ? ['ten' => $nh->ten, 'slug' => $nh->slug, 'thu_tu' => (int) $nh->thu_tu] : null,
                    'danh_muc'       => ($dm && $dm->hien) ? ['ten' => $dm->ten, 'slug' => $dm->slug, 'icon' => $dm->icon, 'thu_tu' => (int) $dm->thu_tu] : null,
                    'gia'            => (int) $p->gia,
                    'gia_goc'        => (int) $p->gia_goc,
                    // Giá sỉ CHỈ trả cho đại lý đã đăng nhập (null với khách thường)
                    'gia_si'         => $daiLy ? (int) $p->gia_si : null,
                    'mota'           => $p->mota ?: [],
                    'mo_ta_ngan'     => $p->mo_ta_ngan,
                    'mo_ta_dai'      => $p->mo_ta_dai,
                    'variants'       => collect($p->variants ?: [])->map(function ($v) {
                        $out = ['ten' => (string) ($v['ten'] ?? ''), 'gia' => (int) ($v['gia'] ?? 0)];
                        if (array_key_exists('gia_them', $v)) $out['gia_them'] = (int) $v['gia_them'];
                        if (!empty($v['anh'])) {
                            $out['anh']    = asset('storage/' . $v['anh']); // bản lớn (đổi ảnh chính)
                            $out['anhNho'] = $this->thumbUrl($v['anh']);    // bản nhỏ (ô chọn 40px)
                        }
                        return $out;
                    })->all(),
                    'khac_ten'       => (bool) $p->khac_ten,
                    'dat_lam'        => (bool) $p->dat_lam,
                    'nhan'           => $p->nhan,
                    // Giống tranhdali.vn: chưa có đánh giá thì mặc định 5.0 sao.
                    // (sao cast decimal:1 trả chuỗi "0.0" — phải ép số rồi so >0, không dùng ?:)
                    'sao'            => (float) $p->sao > 0 ? (float) $p->sao : 5.0,
                    'da_ban'         => (int) $p->da_ban, // tự cộng khi đơn hoàn tất
                    // Ảnh trả về URL đầy đủ — front-end dùng thẳng, không dựng URL PocketBase nữa
                    'anh'            => collect($p->anh ?: [])->map(fn ($a) => asset('storage/' . $a))->all(),
                    // Bản thu nhỏ ~400px cho thẻ/gallery (fallback ảnh lớn nếu chưa có thumbnail)
                    'anhNho'         => collect($p->anh ?: [])->map(fn ($a) => $this->thumbUrl($a))->all(),
                    'payment_policy' => $p->payment_policy ?: 'deposit_50', // mặc định cọc 50% trước
                    'shipping_class' => $p->shipping_class ?: 'standard',
                ];
            });
        return response()->json(['items' => $items])
            ->header('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
    }

    /* ============ Đại lý: đăng nhập / phiên / đăng xuất ============ */

    /** Lấy đại lý từ header X-Dai-Ly-Token (nếu hợp lệ + đang mở khoá). */
    private function daiLyTuRequest(Request $request): ?DaiLy
    {
        $token = trim((string) $request->header('X-Dai-Ly-Token', ''));
        if (strlen($token) < 20) return null;
        return DaiLy::where('token', $token)->where('hien', true)->first();
    }

    private function cors($resp)
    {
        return $resp->header('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
    }

    /** POST /api/3d/dai-ly/login  {sdt, matkhau} -> {ok, token, ten} */
    public function dealerLogin(Request $request)
    {
        $key = 'dl-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 8)) {
            return $this->cors(response()->json(['ok' => false, 'error' => 'Thử lại sau ít phút.'], 429));
        }
        RateLimiter::hit($key, 300);

        $v = $request->validate([
            'sdt'     => 'required|string|max:20',
            'matkhau' => 'required|string|max:100',
        ]);
        $sdt = preg_replace('/[^0-9+]/', '', $v['sdt']);
        $dl  = DaiLy::where('sdt', $sdt)->where('hien', true)->first();
        if (!$dl || !Hash::check($v['matkhau'], $dl->matkhau)) {
            return $this->cors(response()->json(['ok' => false, 'error' => 'Sai số điện thoại hoặc mật khẩu.'], 401));
        }
        $dl->token = Str::random(48);
        $dl->dang_nhap_luc = now();
        $dl->save();
        RateLimiter::clear($key);
        return $this->cors(response()->json(['ok' => true, 'token' => $dl->token, 'ten' => $dl->ten]));
    }

    /** GET /api/3d/dai-ly/me (header token) -> {ok, ten} | 401 */
    public function dealerMe(Request $request)
    {
        $dl = $this->daiLyTuRequest($request);
        if (!$dl) return $this->cors(response()->json(['ok' => false], 401));
        return $this->cors(response()->json(['ok' => true, 'ten' => $dl->ten]));
    }

    /** POST /api/3d/dai-ly/logout (header token) — xoá token phiên. */
    public function dealerLogout(Request $request)
    {
        $dl = $this->daiLyTuRequest($request);
        if ($dl) { $dl->token = null; $dl->save(); }
        return $this->cors(response()->json(['ok' => true]));
    }

    /** URL ảnh thu nhỏ (sp3d/tn/<base>.jpg); nếu chưa có thì trả URL ảnh lớn (không 404). */
    private function thumbUrl(string $rel): string
    {
        $dir  = trim(dirname($rel), '.');
        $base = pathinfo($rel, PATHINFO_FILENAME);
        $tn   = ($dir ? $dir . '/' : '') . 'tn/' . $base . '.jpg';
        return \Storage::disk('public')->exists($tn)
            ? asset('storage/' . $tn)
            : asset('storage/' . $rel);
    }

    /* ============ POST /api/3d/quote ============ */
    public function quote(Request $request)
    {
        $this->guardOrigin($request);
        if (trim((string) $request->input('honeypot', '')) !== '') abort(400, 'Yêu cầu không hợp lệ.');
        // Giới hạn 30 lần/phút theo IP (giống PocketBase)
        if (RateLimiter::tooManyAttempts('quote3d:' . $this->clientIp($request), 30)) abort(429, 'Vui lòng thử lại sau một phút.');
        RateLimiter::hit('quote3d:' . $this->clientIp($request), 60);

        $priced = $this->priceCart($request);
        $bank   = $this->bankConfig();

        return response()->json([
            'lines'            => $priced['lines'],
            'subtotal'         => $priced['subtotal'],
            'discount'         => $priced['discount'],
            'shipping'         => $priced['shipping'],
            'total'            => $priced['total'],
            'due_now'          => $priced['due_now'],
            'remaining'        => $priced['total'] - $priced['due_now'],
            'payment_mode'     => $priced['mode'],
            'payment_modes'    => in_array('prepaid_100', $priced['policies']) ? ['qr'] : ['cod', 'qr'],
            'shipping_method'  => $priced['shipping_method'],
            'only_subject'     => $priced['only_subject'],
            'qr_required'      => $priced['due_now'] > 0,
            'discount_eligible' => $priced['only_prepaid_eligible'],
            'bank'             => $bank,
        ])->header('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
    }

    /* ============ POST /api/3d/checkout ============ */
    public function checkout(Request $request)
    {
        $this->guardOrigin($request);
        if (trim((string) $request->input('honeypot', '')) !== '') abort(400, 'Yêu cầu không hợp lệ.');

        $clientRef = (string) $request->input('client_ref', '');
        if (!preg_match('/^[A-Za-z0-9_-]{16,80}$/', $clientRef)) abort(400, 'Mã phiên thanh toán không hợp lệ.');

        $c        = $request->input('customer', []);
        $name     = trim((string) ($c['name'] ?? ''));
        $phone    = preg_replace('/[^0-9+]/', '', (string) ($c['phone'] ?? ''));
        $address  = trim((string) ($c['address'] ?? ''));
        $province = trim((string) ($c['province'] ?? ''));
        $note     = trim((string) ($c['note'] ?? ''));
        $email    = trim((string) ($c['email'] ?? ''));
        if (mb_strlen($name) < 2 || mb_strlen($name) > 80 || !preg_match('/^0[0-9]{9}$/', $phone)
            || mb_strlen($address) < 8 || mb_strlen($address) > 300 || mb_strlen($province) > 80
            || mb_strlen($note) > 500 || ($email !== '' && !preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email))) {
            abort(400, 'Thông tin nhận hàng không hợp lệ.');
        }

        // Giới hạn 8 lần / 10 phút theo IP + số điện thoại
        $ckKey = 'checkout3d:' . $this->clientIp($request) . ':' . $phone;
        if (RateLimiter::tooManyAttempts($ckKey, 8)) abort(429, 'Bạn đã thử quá nhiều lần. Vui lòng chờ ít phút.');
        RateLimiter::hit($ckKey, 600);

        // Chống trùng đơn: trả lại đơn cũ nếu client_ref đã có
        $old = Don3d::where('client_ref', $clientRef)->first();
        if ($old) {
            return $this->orderResponse($old, true, 200);
        }

        $priced = $this->priceCart($request);

        $code = 'DL' . substr(now()->format('ymd'), 0, 6) . '-' . strtoupper(Str::random(6));
        $dueNow    = $priced['due_now'];
        $remaining = $priced['total'] - $dueNow;

        try {
            $order = Don3d::create([
                'ma'              => $code,
                'client_ref'      => $clientRef,
                'loai'            => $dueNow === 0 ? 'cod' : ($remaining === 0 ? 'ck' : 'coc'),
                'tt'              => $dueNow === 0 ? 'dang_in' : 'cho_coc',
                'trang_thai_tt'   => $dueNow === 0 ? 'cod' : 'cho_xac_nhan',
                'sp'              => collect($priced['lines'])->map(fn ($l) => $l['ten'] . ' ×' . $l['qty'])->implode('; '),
                'chi_tiet'        => $priced['lines'],
                'tong'            => $priced['total'],
                'giam'            => $priced['discount'],
                'phi_ship'        => $priced['shipping'],
                'tra_ngay'        => $dueNow,
                'con_lai'         => $remaining,
                'phuong_thuc_tt'  => $priced['mode'],
                'shipping_method' => $priced['shipping_method'],
                'nguon'           => 'website-v2',
                'ten'             => $name,
                'sdt'             => $phone,
                'email'           => $email,
                'tinh'            => $province,
                'dia_chi'         => $address,
                'ghi_chu'         => $note,
            ]);
        } catch (\Throwable $ex) {
            // Đua nhau tạo đơn cùng client_ref -> chỉ số unique chặn, trả đơn đã có
            $old = Don3d::where('client_ref', $clientRef)->first();
            if ($old) return $this->orderResponse($old, true, 200);
            throw $ex;
        }

        $bank = $this->bankConfig();
        return response()->json([
            'order_code'     => $code,
            'total'          => $priced['total'],
            'due_now'        => $dueNow,
            'remaining'      => $remaining,
            'payment_status' => $dueNow === 0 ? 'cod' : 'cho_xac_nhan',
            'bank'           => $bank,
            'transfer_note'  => 'DALI ' . $code,
        ], 201)->header('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
    }

    /* ============ POST /api/3d/event ============ */
    public function event(Request $request)
    {
        $this->guardOrigin($request);
        $event = (string) $request->input('event', '');
        $allowed = ['view_product', 'add_cart', 'begin_checkout', 'order_created', 'zalo_confirm', 'order_lookup'];
        if (!in_array($event, $allowed, true)) abort(400, 'Sự kiện không hợp lệ.');
        DB::table('su_kien_3d')->insert(['event' => $event, 'path' => '', 'created_at' => now(), 'updated_at' => now()]);
        return response()->noContent()->header('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
    }

    /* ============ POST /api/3d/order-lookup ============ */
    public function orderLookup(Request $request)
    {
        $this->guardOrigin($request);
        if (trim((string) $request->input('honeypot', '')) !== '') abort(400, 'Yêu cầu không hợp lệ.');
        $code  = strtoupper(trim((string) $request->input('order_code', '')));
        $last4 = preg_replace('/\D/', '', (string) $request->input('phone_last4', ''));
        if (!preg_match('/^DL[0-9]{6}-[A-Z0-9]{4,8}$/', $code) || !preg_match('/^[0-9]{4}$/', $last4)) {
            abort(400, 'Mã đơn hoặc số điện thoại không hợp lệ.');
        }
        $order = Don3d::where('ma', $code)->first();
        if (!$order || substr(preg_replace('/\D/', '', $order->sdt), -4) !== $last4) {
            abort(404, 'Không tìm thấy đơn phù hợp.');
        }
        $lines = collect($order->chi_tiet ?: [])->map(fn ($l) => [
            'ten' => $l['ten'] ?? '', 'qty' => $l['qty'] ?? 0,
            'bien_the' => $l['bien_the'] ?? '', 'khac_ten' => $l['khac_ten'] ?? '',
        ])->all();
        return response()->json([
            'order_code'     => $order->ma,
            'status'         => $order->tt,
            'payment_status' => $order->trang_thai_tt,
            'total'          => (int) $order->tong,
            'due_now'        => (int) $order->tra_ngay,
            'remaining'      => (int) $order->con_lai,
            'shipping_code'  => $order->ma_vc,
            'lines'          => $lines,
            'created'        => optional($order->created_at)->toIso8601String(),
        ])->header('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
    }

    /* ==================== LÕI TÍNH TIỀN (dùng chung) ==================== */

    /**
     * Dựng các dòng hàng + tính subtotal/ship/giảm/cọc. Trả về mảng đầy đủ.
     * Chép nguyên logic từ PocketBase để khớp từng đồng.
     */
    private function priceCart(Request $request): array
    {
        $GIA = $this->config('GIA', [
            'full' => 289000, 'board' => 165000, 'phieu' => 8000, 'custom' => 12000,
            'chan' => 15000, 'boPhieu' => 149000, 'shipPhieu' => 15000, 'freeshipPhieu' => 99000,
        ]);
        $MON   = $this->config('MON', []);
        $input = $request->input('items', []);
        if (!is_array($input) || count($input) < 1 || count($input) > 40) abort(400, 'Giỏ hàng không hợp lệ.');

        $lines = [];
        foreach ($input as $raw) {
            $sku = (string) ($raw['sku'] ?? '');
            $qty = $raw['qty'] ?? null;
            if (!is_int($qty) && !(is_numeric($qty) && (int) $qty == $qty)) abort(400, 'Số lượng không hợp lệ.');
            $qty = (int) $qty;
            if ($qty < 1 || $qty > 100) abort(400, 'Số lượng không hợp lệ.');

            if (str_starts_with($sku, 'product:')) {
                $slug = substr($sku, 8);
                $product = Sp3d::where('slug', $slug)->first();
                if (!$product || !$product->hien) abort(400, 'Sản phẩm không còn bán.');
                $variants = $product->variants ?: [];
                $idx = array_key_exists('variantIndex', (array) $raw) ? (int) $raw['variantIndex'] : -1;
                $variant = null;
                if ($idx >= 0) {
                    if (!isset($variants[$idx])) abort(400, 'Biến thể không hợp lệ.');
                    $variant = $variants[$idx];
                }
                $pers = trim((string) ($raw['personalization'] ?? ''));
                if (mb_strlen($pers) > 60) abort(400, 'Tên khắc quá dài.');
                if ($pers !== '' && !$product->khac_ten) abort(400, 'Sản phẩm này không hỗ trợ khắc tên.');
                $base = (int) $product->gia;
                if ($variant && array_key_exists('gia_them', $variant)) {
                    $unit = $base + (int) ($variant['gia_them'] ?? 0);
                } elseif ($variant) {
                    $vp = (int) ($variant['gia'] ?? -1);
                    $unit = $vp >= 0 ? $vp : $base;
                } else {
                    $unit = $base;
                }
                $line = [
                    'sku' => $sku, 'ten' => $product->ten, 'qty' => $qty, 'don_gia' => $unit,
                    'bien_the' => $variant ? (string) ($variant['ten'] ?? '') : null,
                    'khac_ten' => $pers,
                    'payment_policy' => $product->payment_policy ?: 'deposit_50', // mặc định cọc 50% trước
                    'shipping_class' => $product->shipping_class ?: 'standard',
                ];
            } elseif (in_array($sku, ['tkb:full', 'tkb:board', 'subject:predefined', 'subject:custom', 'subject:leg', 'subject:set'], true)) {
                $subjectIndex = isset($raw['subjectIndex']) ? (int) $raw['subjectIndex'] : -1;
                $customText   = trim((string) ($raw['customText'] ?? ''));
                $pers         = trim((string) ($raw['personalization'] ?? ''));
                if ($sku === 'subject:predefined' && !isset($MON[$subjectIndex])) abort(400, 'Môn học không hợp lệ.');
                if ($sku === 'subject:custom' && ($customText === '' || mb_strlen($customText) > 45)) abort(400, 'Nội dung phiếu không hợp lệ.');
                if (mb_strlen($pers) > 60) abort(400, 'Nội dung khắc tên không hợp lệ.');
                $subjectName = '';
                if (isset($MON[$subjectIndex])) {
                    $m = $MON[$subjectIndex];
                    $subjectName = (string) (is_array($m) ? ($m['ten'] ?? $m[0] ?? '') : $m);
                }
                $map = [
                    'tkb:full'           => ['Bảng TKB khung', (int) $GIA['full'], 'deposit_50', 'standard'],
                    'tkb:board'          => ['Bảng TKB để bàn', (int) $GIA['board'], 'deposit_50', 'standard'],
                    'subject:predefined' => ['Phiếu môn ' . $subjectName, (int) $GIA['phieu'], 'cod_or_prepaid_10', 'subject_card'],
                    'subject:custom'     => ['Phiếu cá nhân hóa: ' . $customText, (int) $GIA['custom'], 'prepaid_100', 'subject_card'],
                    'subject:leg'        => ['Chân đế phiếu', (int) $GIA['chan'], 'cod_or_prepaid_10', 'subject_card'],
                    'subject:set'        => ['Bộ 20 phiếu môn học', (int) $GIA['boPhieu'], 'cod_or_prepaid_10', 'subject_card'],
                ];
                $spec = $map[$sku];
                $line = ['sku' => $sku, 'ten' => $spec[0], 'qty' => $qty, 'don_gia' => $spec[1],
                         'payment_policy' => $spec[2], 'shipping_class' => $spec[3]];
                if ($sku === 'subject:predefined') $line['mon_hoc'] = $subjectName;
                if ($sku === 'subject:custom') $line['noi_dung'] = $customText;
                if ($pers !== '') $line['khac_ten'] = $pers;
            } else {
                abort(400, 'SKU không hợp lệ.');
            }
            $line['thanh_tien'] = $line['don_gia'] * $qty;
            $lines[] = $line;
        }

        $subtotal    = array_sum(array_column($lines, 'thanh_tien'));
        $onlySubject = collect($lines)->every(fn ($l) => $l['shipping_class'] === 'subject_card');
        $shippingMethod = (string) $request->input('shipping_method', 'standard');
        if ($shippingMethod !== 'standard' && $shippingMethod !== 'grouped') abort(400, 'Phương án giao hàng không hợp lệ.');
        if ($shippingMethod === 'grouped' && !$onlySubject) abort(400, 'Gộp chuyến chỉ áp dụng cho phiếu.');

        $shipping = $onlySubject
            ? ($subtotal >= (int) ($GIA['freeshipPhieu'] ?? 99000) ? 0 : ($shippingMethod === 'grouped' ? 10000 : (int) ($GIA['shipPhieu'] ?? 15000)))
            : ($subtotal >= 299000 ? 0 : 30000);

        $policies = array_column($lines, 'payment_policy');
        $onlyPrepaidEligible = collect($policies)->every(fn ($p) => $p === 'cod_or_prepaid_10');
        $mode = (string) $request->input('payment_mode', 'cod');
        if ($mode !== 'cod' && $mode !== 'qr') abort(400, 'Phương thức thanh toán không hợp lệ.');
        if (in_array('prepaid_100', $policies) && $mode !== 'qr') abort(400, 'Phiếu cá nhân hóa cần thanh toán trước.');

        $discount = ($mode === 'qr' && $onlyPrepaidEligible) ? (int) round($subtotal * 0.1) : 0;
        $total    = $subtotal - $discount + $shipping;

        $dueNow = 0;
        if ($mode === 'qr' && $onlyPrepaidEligible) {
            $dueNow = $total;
        } else {
            foreach ($lines as $l) {
                if ($l['payment_policy'] === 'prepaid_100') $dueNow += $l['thanh_tien'];
                if ($l['payment_policy'] === 'deposit_50')  $dueNow += (int) (ceil($l['thanh_tien'] * 0.5 / 1000) * 1000);
            }
            if ($dueNow > 0) $dueNow += $shipping;
        }

        return [
            'lines' => $lines, 'subtotal' => $subtotal, 'discount' => $discount,
            'shipping' => $shipping, 'total' => $total, 'due_now' => $dueNow,
            'mode' => $mode, 'shipping_method' => $shippingMethod,
            'only_subject' => $onlySubject, 'policies' => $policies,
            'only_prepaid_eligible' => $onlyPrepaidEligible,
        ];
    }

    private function orderResponse(Don3d $o, bool $reused, int $status)
    {
        return response()->json([
            'order_code'     => $o->ma,
            'total'          => (int) $o->tong,
            'due_now'        => (int) $o->tra_ngay,
            'remaining'      => (int) $o->con_lai,
            'payment_status' => $o->trang_thai_tt,
            'reused'         => $reused,
        ], $status)->header('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
    }

    private function config(string $key, $fallback)
    {
        $row = DB::table('cau_hinh_3d')->where('khoa', $key)->value('gia_tri');
        if ($row === null) return $fallback;
        $v = json_decode($row, true);
        return $v === null ? $fallback : $v;
    }

    /** Ngân hàng nhận tiền — DÙNG CHUNG với cài đặt tranhdali.vn (admin_settings). */
    private function bankConfig(): array
    {
        $s = DB::table('admin_settings')->whereIn('key', ['bank_id', 'bank_acc', 'bank_name'])->pluck('value', 'key');
        return [
            'bin' => trim((string) ($s['bank_id'] ?? '')) ?: 'MSB',
            'stk' => trim((string) ($s['bank_acc'] ?? '')) ?: '13001011945010',
            'ten' => trim((string) ($s['bank_name'] ?? '')) ?: 'DOAN ANH TUAN',
        ];
    }

    /**
     * IP khach that (web khach goi qua proxy noi bo cua 3d.tranhdali.vn, nen
     * $request->ip() la IP may chu; IP khach o X-Forwarded-For hop dau).
     */
    private function clientIp(Request $request): string
    {
        $xff = $request->header('X-Forwarded-For');
        if ($xff) {
            $first = trim(explode(',', $xff)[0]);
            if ($first !== '') return $first;
        }
        return $request->header('X-Real-IP') ?: $request->ip();
    }

    private function guardOrigin(Request $request): void
    {
        $origin = $request->header('Origin', '');
        if ($origin !== '' && $origin !== self::ALLOWED_ORIGIN) abort(403, 'Origin không được phép.');
    }
}
