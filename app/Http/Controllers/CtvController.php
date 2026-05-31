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
        $categories = $products->pluck('category.name','category_id')->filter()->unique()->sort();
        return view('ctv.order-create', compact('ctv', 'products', 'sizes', 'categories'));
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
        foreach ($items as $row) {
            $product = Product::find($row['product_id']);
            if (!$product) continue;
            $size    = !empty($row['size_id']) ? Size::find($row['size_id']) : null;
            $price   = $size ? (int) $size->price : (int) $product->price;
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
            'ship_fee'             => 0,
            'total'                => $subtotal,
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

        // Ghi nhận hoa hồng
        $commission = (int) round($subtotal * $ctv->commission_rate / 100);
        $order->update(['affiliate_commission' => $commission]);
        $ctv->increment('total_earned', $commission);
        $ctv->increment('total_orders');

        $itemCount = count($lineItems);
        return redirect()->route('ctv.dashboard')->with('success',
            "Đã lên đơn {$code} ({$itemCount} sản phẩm) thành công! Hoa hồng: +" . number_format($commission, 0, ',', '.') . 'đ');
    }

    // ─── TRANG RÚT TIỀN ────────────────────────
    public function withdrawPage(Request $request)
    {
        $ctv = $request->attributes->get('ctv');
        $withdrawals = $ctv->withdrawals()->paginate(15);
        return view('ctv.withdraw', compact('ctv', 'withdrawals'));
    }

    // ─── XỬ LÝ RÚT TIỀN ───────────────────────
    public function withdraw(Request $request)
    {
        $ctv = $request->attributes->get('ctv');

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

        return redirect()->route('ctv.withdraw.page')->with('success', 'Đã gửi yêu cầu rút ' . number_format($data['amount'], 0, ',', '.') . 'đ. Quản trị viên sẽ duyệt và chuyển khoản sớm.');
    }
}
