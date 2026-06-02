<?php
namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Size;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CtvController extends Controller
{
    // ─── ĐĂNG NHẬP ─────────────────────────────
    public function showLogin()
    {
        if (session('ctv_id')) return redirect()->route('ctv.dashboard');
        $settings = \Illuminate\Support\Facades\DB::table('admin_settings')->pluck('value','key');
        return view('ctv.login', compact('settings'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);
        $ctv = Affiliate::where('phone', trim($data['phone']))->first();
        if (!$ctv || !$ctv->password || !Hash::check($data['password'], $ctv->password)) {
            return back()->with('error', 'Số điện thoại hoặc mật khẩu không đúng.')->withInput();
        }
        if (!$ctv->is_active) {
            return back()->with('error', 'Tài khoản đang bị khoá. Liên hệ quản trị viên.');
        }
        session(['ctv_id' => $ctv->id]);
        return redirect()->route('ctv.dashboard');
    }

    public function logout()
    {
        session()->forget('ctv_id');
        return redirect()->route('ctv.login')->with('success', 'Đã đăng xuất.');
    }

    // ─── TRANG TỔNG QUAN ───────────────────────
    public function dashboard(Request $request)
    {
        $ctv = $request->attributes->get('ctv');
        $orders = Order::where('affiliate_code', $ctv->code)->latest()->limit(10)->get();
        $withdrawals = $ctv->withdrawals()->limit(10)->get();
        return view('ctv.dashboard', compact('ctv', 'orders', 'withdrawals'));
    }

    // ─── DANH SÁCH ĐƠN CỦA CTV ─────────────────
    public function orders(Request $request)
    {
        $ctv = $request->attributes->get('ctv');
        $orders = Order::where('affiliate_code', $ctv->code)
            ->withCount('items')->latest()->paginate(20);
        return view('ctv.orders', compact('ctv', 'orders'));
    }

    // ─── LÊN ĐƠN ───────────────────────────────
    public function createOrder(Request $request)
    {
        $ctv = $request->attributes->get('ctv');
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $sizes = Size::where('is_active', true)->orderBy('sort_order')->get()->keyBy('id');
        // Đại lý: hiển thị giá sỉ riêng thay cho giá lẻ (khớp với cách tính khi lên đơn)
        if ($ctv->isAgent()) {
            $agentMap = $ctv->agentPrices()->pluck('price', 'size_id')->toArray();
            foreach ($sizes as $sz) {
                if (!empty($agentMap[$sz->id])) $sz->price = (int) $agentMap[$sz->id];
            }
        }
        $categories = $products->pluck('category.name','category_id')->filter()->unique()->sort();
        $settings = \Illuminate\Support\Facades\DB::table('admin_settings')->pluck('value','key');
        return view('ctv.order-create', compact('ctv', 'products', 'sizes', 'categories', 'settings'));
    }

    public function storeOrder(Request $request)
    {
        $ctv = $request->attributes->get('ctv');

        $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.size_id'    => 'nullable|exists:sizes,id',
            'items.*.qty'        => 'required|integer|min:1|max:99',
        ]);

        $items = $request->input('items', []);

        // Tính tổng
        $subtotal = 0;
        $lineItems = [];
        // Đại lý: lấy bảng giá sỉ riêng (size_id => giá). CTV thường: rỗng.
        $agentMap = $ctv->isAgent() ? $ctv->agentPrices()->pluck('price', 'size_id')->toArray() : [];
        foreach ($items as $row) {
            $product = Product::find($row['product_id']);
            if (!$product) continue;
            $size    = !empty($row['size_id']) ? Size::find($row['size_id']) : null;
            $price   = ($size && !empty($agentMap[$size->id]))
                       ? (int) $agentMap[$size->id]                       // giá sỉ đại lý
                       : ($size ? (int) $size->price : (int) $product->price); // giá lẻ
            $qty     = (int) $row['qty'];
            $lineSub = $price * $qty;
            $subtotal += $lineSub;
            $lineItems[] = [
                'product'      => $product,
                'size'         => $size,
                'price'        => $price,
                'qty'          => $qty,
                'subtotal'     => $lineSub,
            ];
        }

        if (empty($lineItems)) {
            return back()->with('error', 'Vui lòng chọn ít nhất 1 sản phẩm.');
        }

        // Tính phí ship (giống website chính)
        $settings   = \Illuminate\Support\Facades\DB::table('admin_settings')->pluck('value','key');
        $freeFrom   = (int)($settings['free_ship_from'] ?? 299000);
        $shipFee    = (int)($settings['ship_fee'] ?? 30000);
        $ship       = $subtotal >= $freeFrom ? 0 : $shipFee;
        $total      = $subtotal + $ship;

        $code = 'DALI-' . strtoupper(substr(uniqid(), -6));

        $order = Order::create([
            'code'                 => $code,
            'customer_name'        => $request->input('customer_name'),
            'customer_phone'       => $request->input('customer_phone'),
            'customer_city'        => $request->input('customer_city', ''),
            'customer_address'     => $request->input('customer_address', ''),
            'note'                 => $request->input('note', ''),
            'affiliate_code'       => $ctv->code,
            'affiliate_commission' => 0,
            'payment_method'       => $request->input('payment', 'COD'),
            'payment_status'       => 'pending',
            'status'               => 'new',
            'subtotal'             => $subtotal,
            'discount'             => 0,
            'ship_fee'             => $ship,
            'total'                => $total,
        ]);

        foreach ($lineItems as $li) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $li['product']->id,
                'product_name' => $li['product']->name,
                'product_size' => $li['size']?->name ?? '',
                'price'        => $li['price'],
                'quantity'     => $li['qty'],
                'subtotal'     => $li['subtotal'],
            ]);
            // Tăng sold_count
            $li['product']->increment('sold_count', $li['qty']);
        }

        // Đại lý: KHÔNG hoa hồng + bắt cọc %. CTV: hoa hồng %.
        $commission = $ctv->isAgent() ? 0 : (int) round($total * $ctv->commission_rate / 100);
        $deposit = 0;
        if ($ctv->isAgent()) {
            $depPct  = $ctv->effectiveDepositPercent((int) ($settings['agent_deposit_percent'] ?? 20));
            $deposit = (int) round($total * $depPct / 100);
        }
        $order->update(['affiliate_commission' => $commission, 'deposit' => $deposit]);
        if ($commission > 0) $ctv->increment('total_earned', $commission);
        $ctv->increment('total_orders');

        $itemCount = count($lineItems);
        // Đại lý có cọc → sang trang đặt cọc (QR). CTV → về bảng điều khiển.
        if ($ctv->isAgent() && $deposit > 0) {
            return redirect()->route('ctv.order.deposit', $code);
        }
        return redirect()->route('ctv.dashboard')->with('success',
            "Đã lên đơn {$code} ({$itemCount} sản phẩm) thành công!"
            . ($commission > 0 ? ' Hoa hồng: +' . number_format($commission, 0, ',', '.') . 'đ' : ''));
    }

    // ─── TRANG ĐẶT CỌC ĐẠI LÝ ──────────────────
    public function orderDeposit(Request $request, $code)
    {
        $ctv   = $request->attributes->get('ctv');
        $order = Order::where('code', $code)->where('affiliate_code', $ctv->code)->firstOrFail();
        $settings = \Illuminate\Support\Facades\DB::table('admin_settings')->pluck('value', 'key');
        return view('ctv.order-deposit', compact('ctv', 'order', 'settings'));
    }

    // ─── TRANG RÚT TIỀN ────────────────────────
    public function withdrawPage(Request $request)
    {
        $ctv = $request->attributes->get('ctv');
        if ($ctv->isAgent()) {
            return redirect()->route('ctv.dashboard')->with('error', 'Tài khoản đại lý không có hoa hồng để rút (bán trực tiếp giá sỉ).');
        }
        $withdrawals = $ctv->withdrawals()->paginate(15);
        return view('ctv.withdraw', compact('ctv', 'withdrawals'));
    }

    // ─── XỬ LÝ RÚT TIỀN ───────────────────────
    public function withdraw(Request $request)
    {
        $ctv = $request->attributes->get('ctv');

        if ($ctv->isAgent()) {
            return redirect()->route('ctv.dashboard')->with('error', 'Tài khoản đại lý không có hoa hồng để rút.');
        }

        if (!$ctv->bank_acc || !$ctv->bank_name) {
            return back()->with('error', 'Bạn chưa có thông tin ngân hàng. Vui lòng liên hệ quản trị viên cập nhật trước khi rút.');
        }

        $available = $ctv->available;
        $data = $request->validate([
            'amount' => "required|integer|min:50000|max:$available",
            'note'   => 'nullable|string|max:255',
        ], [
            'amount.max' => 'Số tiền vượt quá số dư khả dụng (' . number_format($available, 0, ',', '.') . 'đ).',
            'amount.min' => 'Số tiền rút tối thiểu là 50.000đ.',
        ]);

        Withdrawal::create([
            'affiliate_id' => $ctv->id,
            'amount'       => $data['amount'],
            'bank_name'    => $ctv->bank_name,
            'bank_acc'     => $ctv->bank_acc,
            'bank_owner'   => $ctv->bank_owner,
            'status'       => 'pending',
            'note'         => $data['note'] ?? null,
        ]);

        // ── Telegram notification ──
        $settings  = \Illuminate\Support\Facades\DB::table('admin_settings')->pluck('value', 'key');
        $tgToken   = $settings['tg_token'] ?? null;
        $tgChat    = $settings['tg_chat_id'] ?? null;
        if ($tgToken && $tgChat) {
            $msg = "💸 YÊU CẦU RÚT TIỀN CTV\n"
                 . "━━━━━━━━━━━━━━━━━━\n"
                 . "👤 CTV: " . $ctv->name . " (" . $ctv->phone . ")\n"
                 . "💰 Số tiền: " . number_format($data['amount'], 0, ',', '.') . "đ\n"
                 . "🏦 Ngân hàng: " . $ctv->bank_name . "\n"
                 . "💳 Số TK: " . $ctv->bank_acc . "\n"
                 . "👤 Chủ TK: " . $ctv->bank_owner . "\n"
                 . "🕐 Thời gian: " . now()->format('H:i d/m/Y') . "\n"
                 . "━━━━━━━━━━━━━━━━━━\n"
                 . "Vào trang admin để duyệt yêu cầu.";
            $ch = curl_init("https://api.telegram.org/bot{$tgToken}/sendMessage");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'chat_id' => $tgChat,
                'text'    => $msg,
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);
        }

        return redirect()->route('ctv.withdraw.page')->with('success', 'Đã gửi yêu cầu rút ' . number_format($data['amount'], 0, ',', '.') . 'đ. Quản trị viên sẽ duyệt và chuyển khoản sớm.');
    }

    // ─── HỒ SƠ CTV ─────────────────────────────
    public function profile(Request $request)
    {
        $ctv = $request->attributes->get('ctv');
        return view('ctv.profile', compact('ctv'));
    }

    public function updateProfile(Request $request)
    {
        $ctv = $request->attributes->get('ctv');

        $data = $request->validate([
            'bank_name'            => 'required|string|max:100',
            'bank_acc'             => 'required|string|max:50',
            'bank_owner'           => 'required|string|max:100',
            'new_password'         => 'nullable|string|min:6|confirmed',
        ], [
            'bank_name.required'   => 'Vui lòng nhập tên ngân hàng.',
            'bank_acc.required'    => 'Vui lòng nhập số tài khoản.',
            'bank_owner.required'  => 'Vui lòng nhập tên chủ tài khoản.',
            'new_password.min'     => 'Mật khẩu mới tối thiểu 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $update = [
            'bank_name'  => $data['bank_name'],
            'bank_acc'   => $data['bank_acc'],
            'bank_owner' => $data['bank_owner'],
        ];

        if (!empty($data['new_password'])) {
            $update['password'] = Hash::make($data['new_password']);
        }

        $ctv->update($update);

        return redirect()->route('ctv.profile')->with('success', 'Đã cập nhật hồ sơ thành công.');
    }
}
