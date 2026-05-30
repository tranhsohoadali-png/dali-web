<?php
namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Post;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;

class WebsiteController extends Controller
{
    public function index()
    {
        $hero       = HeroSection::first();
        $categories = Category::where('is_active',true)->withCount(['products'])->orderBy('sort_order')->get();
        $products   = Product::with('category')->where('is_active',true)->orderBy('sort_order')->orderBy('sold_count','desc')->get();
        $settings   = DB::table('admin_settings')->pluck('value','key');
        return view('website.index', compact('hero','categories','products','settings'));
    }

    public function products(Request $request)
    {
        $query = Product::with('category')->where('is_active',true);
        if ($request->filled('category')) $query->whereHas('category', fn($q) => $q->where('slug',$request->category));
        if ($request->filled('search'))   $query->where('name','like','%'.$request->search.'%');
        if ($request->filled('sort')) {
            match($request->sort) {
                'price_asc'  => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'new'        => $query->latest(),
                default      => $query->orderBy('sort_order')->orderByDesc('sold_count'),
            };
        } else {
            $query->orderBy('sort_order')->orderByDesc('sold_count');
        }
        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active',true)->orderBy('sort_order')->get();
        $settings   = DB::table('admin_settings')->pluck('value','key');
        return view('website.products', compact('products','categories','settings'));
    }

    public function product(Product $product)
    {
        if (!$product->is_active) abort(404);
        $related  = Product::where('category_id',$product->category_id)->where('id','!=',$product->id)->where('is_active',true)->take(4)->get();
        $settings = DB::table('admin_settings')->pluck('value','key');
        return view('website.product', compact('product','related','settings'));
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        if ($request->filled('code')) {
            $order = Order::with('items')->where('code', strtoupper(trim($request->code)))->first();
        }
        $settings = \DB::table('admin_settings')->pluck('value','key');
        return view('website.order-tracking', compact('order','settings'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'product_id'     => 'nullable|exists:products,id',
            'product_name'   => 'required|string',
            'product_size'   => 'nullable|string',
            'price'          => 'required|integer|min:0',
            'quantity'       => 'required|integer|min:1',
            'customer_name'  => 'required|string',
            'customer_phone' => 'required|string',
            'customer_city'  => 'required|string',
            'customer_addr'  => 'required|string',
            'note'           => 'nullable|string',
            'payment'        => 'required|in:COD,BANK',
        ]);

        // Handle coupon
        $couponCode     = strtoupper(trim($request->input('coupon_code', '')));
        $couponDiscount = 0;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                $tempSub = (int)$request->input('price') * (int)$request->input('quantity');
                [$ok] = $coupon->isValid($tempSub);
                if ($ok) {
                    $couponDiscount = $coupon->calcDiscount($tempSub);
                    $coupon->increment('used_count');
                }
            }
        }

        $qty      = $data['quantity'];
        $price    = $data['price'];
        $sub      = $price * $qty;
        $settings   = \DB::table('admin_settings')->pluck('value','key');
        $discPct    = (int)($settings['discount_bank'] ?? 5);
        $discount   = $data['payment'] === 'BANK' ? (int)round($sub * $discPct / 100) : 0;
        $discount  += $couponDiscount; // add coupon discount
        $after    = $sub - $discount;
        $ship     = $after >= 299000 ? 0 : 30000;
        $total    = $after + $ship;
        $code     = 'DALI-' . substr(time(), -6);

        $order = Order::create([
            'code'             => $code,
            'customer_name'    => $data['customer_name'],
            'customer_phone'   => $data['customer_phone'],
            'customer_city'    => $data['customer_city'],
            'customer_address' => $data['customer_addr'],
            'note'             => $data['note'] ?? null,
            'payment_method'   => $data['payment'],
            'payment_status'   => 'pending',
            'status'           => 'new',
            'subtotal'         => $sub,
            'discount'         => $discount,
            'ship_fee'         => $ship,
            'total'            => $total,
        ]);

        OrderItem::create([
            'order_id'     => $order->id,
            'product_id'   => $data['product_id'] ?? null,
            'product_name' => $data['product_name'],
            'product_size' => $data['product_size'] ?? '',
            'price'        => $price,
            'quantity'     => $qty,
            'subtotal'     => $sub,
        ]);

        // Tăng sold_count
        if (!empty($data['product_id'])) {
            Product::where('id',$data['product_id'])->increment('sold_count', $qty);
        }

        return response()->json(['success' => true, 'code' => $code, 'total' => $total]);
    }

    public function confirmPayment(Request $request)
    {
        $code  = strtoupper(trim($request->input('code', '')));
        $order = Order::where('code', $code)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
        }
        // Update payment status to waiting confirmation
        if ($order->payment_status === 'pending') {
            $order->update(['payment_status' => 'pending']); // Already pending, just notify
        }

        // Send Telegram notification to shop owner
        $settings = \DB::table('admin_settings')->pluck('value', 'key');
        $tgToken  = $settings['tg_token']  ?? '';
        $tgChat   = $settings['tg_chat_id'] ?? '';

        if ($tgToken && $tgChat) {
            $confirmUrl = 'https://api.telegram.org/bot' . $tgToken
                . '/sendMessage?chat_id=' . urlencode($tgChat)
                . '&text=' . urlencode(
                    "✅ ĐÃ XÁC NHẬN THANH TOÁN\n"
                  . "━━━━━━━━━━━━━━━━━━\n"
                  . "📦 Đơn: " . $order->code . "\n"
                  . "👤 Khách: " . $order->customer_name . " – " . $order->customer_phone . "\n"
                  . "💰 Tổng: " . number_format($order->total, 0, ',', '.') . "đ\n"
                  . "📍 Giao: " . $order->customer_address . ", " . $order->customer_city . "\n"
                  . "━━━━━━━━━━━━━━━━━━\n"
                  . "🚚 Vui lòng chuẩn bị đóng gói và giao hàng!"
                );

            $keyboard = json_encode(['inline_keyboard' => [[
                ['text' => '✅ Đã nhận tiền – Xác nhận đơn ' . $order->code, 'url' => $confirmUrl]
            ]]]);

            $ownerMsg = "💰 KHÁCH XÁC NHẬN ĐÃ CHUYỂN KHOẢN\n"
                . "━━━━━━━━━━━━━━━━━━\n"
                . "📦 Đơn: " . $order->code . "\n"
                . "👤 Tên: " . $order->customer_name . "\n"
                . "📞 SĐT: " . $order->customer_phone . "\n"
                . "💳 Tổng: " . number_format($order->total, 0, ',', '.') . "đ\n"
                . "📍 Giao: " . $order->customer_address . ", " . $order->customer_city . "\n"
                . "━━━━━━━━━━━━━━━━━━\n"
                . "👆 Kiểm tra TK ngân hàng và bấm nút bên dưới để xác nhận!";

            $ch = curl_init("https://api.telegram.org/bot{$tgToken}/sendMessage");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'chat_id'      => $tgChat,
                'text'         => $ownerMsg,
                'reply_markup' => $keyboard,
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);
        }

        return response()->json(['success' => true]);
    }


    public function checkCoupon(Request $request)
    {
        $code   = strtoupper(trim($request->input('code', '')));
        $amount = (int) $request->input('amount', 0);
        if (!$code) {
            return response()->json(['valid' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
        }
        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Mã giảm giá không tồn tại']);
        }
        [$ok, $msg] = $coupon->isValid($amount);
        if (!$ok) {
            return response()->json(['valid' => false, 'message' => $msg]);
        }
        $discount = $coupon->calcDiscount($amount);
        return response()->json([
            'valid'       => true,
            'code'        => $coupon->code,
            'label'       => $coupon->label,
            'discount'    => $discount,
            'message'     => 'Áp dụng thành công: ' . $coupon->label,
        ]);
    }

    public function submitReview(Request $request)
    {
        $data = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'customer_name' => 'required|string|max:100',
            'customer_phone'=> 'nullable|string|max:20',
            'rating'        => 'required|integer|between:1,5',
            'content'       => 'nullable|string|max:1000',
            'order_code'    => 'nullable|string|max:20',
            'image'         => 'nullable|image|max:5120',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('reviews', 'public');
        }
        $data['is_approved'] = false;
        Review::create($data);
        return response()->json(['success' => true, 'message' => 'Cảm ơn bạn đã chia sẻ! Đánh giá sẽ hiển thị sau khi được admin duyệt.']);
    }


    public function sitemap()
    {
        $products   = Product::where('is_active', true)->get(['slug','updated_at']);
        $categories = Category::where('is_active', true)->get(['slug','updated_at']);
        return response()->view('website.sitemap', compact('products','categories'))
               ->header('Content-Type', 'text/xml');
    }


    // ── CART ──────────────────────────────────
    public function cart()
    {
        $cart     = session('cart', []);
        $settings = \DB::table('admin_settings')->pluck('value','key');
        return view('website.cart', compact('cart','settings'));
    }

    public function addToCart(Request $request)
    {
        $productId = (int) $request->input('product_id');
        $qty       = max(1, (int) $request->input('quantity', 1));
        $product   = Product::find($productId);
        if (!$product) {
            return response()->json(['success'=>false,'message'=>'Sản phẩm không tồn tại']);
        }
        $cart = session('cart', []);
        $key  = 'p' . $productId;
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $qty;
        } else {
            $cart[$key] = [
                'product_id'  => $productId,
                'name'        => $product->name,
                'size'        => $product->size,
                'price'       => $product->price,
                'main_image'  => $product->main_image,
                'quantity'    => $qty,
            ];
        }
        session(['cart' => $cart]);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['success'=>true,'count'=>$count,'message'=>'Đã thêm vào giỏ hàng!']);
    }

    public function updateCart(Request $request)
    {
        $key  = 'p' . (int)$request->input('product_id');
        $qty  = max(0, (int)$request->input('quantity', 0));
        $cart = session('cart', []);
        if ($qty === 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $cart[$key]['quantity'] = $qty;
        }
        session(['cart' => $cart]);
        $count = array_sum(array_column($cart, 'quantity'));
        $subtotal = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'], $cart));
        return response()->json(['success'=>true,'count'=>$count,'subtotal'=>$subtotal]);
    }

    public function removeFromCart(Request $request)
    {
        $key  = 'p' . (int)$request->input('product_id');
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['success'=>true,'count'=>$count]);
    }

    public function cartCount()
    {
        $cart  = session('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['count'=>$count]);
    }

    public function checkoutFromCart(Request $request)
    {
        // Same as placeOrder but handle multiple items
        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['success'=>false,'message'=>'Giỏ hàng trống!']);
        }

        $settings = \DB::table('admin_settings')->pluck('value','key');
        $discPct  = (int)($settings['discount_bank'] ?? 5);

        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $discount = $request->input('payment') === 'BANK' ? (int)round($subtotal * $discPct / 100) : 0;

        // Coupon
        $couponCode     = strtoupper(trim($request->input('coupon_code', '')));
        $couponDiscount = 0;
        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                [$ok] = $coupon->isValid($subtotal);
                if ($ok) {
                    $couponDiscount = $coupon->calcDiscount($subtotal);
                    $coupon->increment('used_count');
                }
            }
        }

        // Affiliate
        $affCode       = strtoupper(trim($request->input('affiliate_code', '') ?: session('affiliate_code', '')));
        $affCommission = 0;
        $affiliate     = null;
        if ($affCode) {
            $affiliate = Affiliate::where('code', $affCode)->where('is_active', true)->first();
        }

        $afterDisc = $subtotal - $discount - $couponDiscount;
        $freeShip  = (int)($settings['free_ship_from'] ?? 299000);
        $shipFee   = $afterDisc >= $freeShip ? 0 : (int)($settings['ship_fee'] ?? 30000);
        $total     = $afterDisc + $shipFee;
        $code      = 'DALI-' . strtoupper(substr(uniqid(), -6));

        $order = Order::create([
            'code'              => $code,
            'customer_name'     => $request->input('customer_name'),
            'customer_phone'    => $request->input('customer_phone'),
            'customer_city'     => $request->input('customer_city'),
            'customer_address'  => $request->input('customer_addr'),
            'note'              => $request->input('note', ''),
            'coupon_code'       => $couponCode ?: null,
            'coupon_discount'   => $couponDiscount,
            'affiliate_code'    => $affCode ?: null,
            'affiliate_commission' => 0,
            'payment_method'    => $request->input('payment','COD'),
            'payment_status'    => 'pending',
            'status'            => 'new',
            'subtotal'          => $subtotal,
            'discount'          => $discount,
            'ship_fee'          => $shipFee,
            'total'             => $total,
        ]);

        // Create order items from cart
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['product_id'],
                'product_name' => $item['name'],
                'product_size' => $item['size'] ?? '',
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $item['price'] * $item['quantity'],
            ]);
        }

        // Record affiliate commission
        if ($affiliate) {
            $commission = (int)round($total * $affiliate->commission_rate / 100);
            $order->update(['affiliate_commission' => $commission]);
            $affiliate->increment('total_earned', $commission);
            $affiliate->increment('total_orders');
        }

        // Clear cart
        session()->forget('cart');

        // Send Telegram
        $itemLines = '';
        foreach ($cart as $item) {
            $itemLines .= "• {$item['name']} x{$item['quantity']} – " . number_format($item['price']*$item['quantity'],0,',','.') . "đ\n";
        }
        $tgToken = $settings['tg_token'] ?? '';
        $tgChat  = $settings['tg_chat_id'] ?? '';
        if ($tgToken && $tgChat) {
            $msg = "🛒 ĐƠN HÀNG GIỎ HÀNG – DALI\n"
                . "━━━━━━━━━━━━━━━━━━\n"
                . "📦 Mã: {$code}\n"
                . "🎨 Sản phẩm:\n{$itemLines}"
                . "💰 Tổng: " . number_format($total,0,',','.') . "đ\n"
                . "👤 {$request->input('customer_name')} – {$request->input('customer_phone')}\n"
                . "📍 {$request->input('customer_addr')}, {$request->input('customer_city')}\n"
                . ($affCode ? "🔗 CTV: {$affCode} (+".number_format($affCommission,0,',','.')."đ hoa hồng)\n" : "")
                . "━━━━━━━━━━━━━━━━━━\n"
                . "💳 " . ($request->input('payment')==='BANK' ? 'QR Chuyển khoản' : 'COD');
            $ch = curl_init("https://api.telegram.org/bot{$tgToken}/sendMessage");
            curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_POSTFIELDS=>json_encode(['chat_id'=>$tgChat,'text'=>$msg])]);
            curl_exec($ch); curl_close($ch);
        }

        return response()->json(['success'=>true,'code'=>$code,'total'=>$total]);
    }

    // ── BLOG ──────────────────────────────────
    public function blog(Request $request)
    {
        $query = Post::where('is_published', true);
        if ($request->filled('category')) $query->where('category', $request->category);
        $posts    = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = Post::where('is_published',true)->distinct()->pluck('category');
        $settings = \DB::table('admin_settings')->pluck('value','key');
        return view('website.blog', compact('posts','categories','settings'));
    }

    public function blogPost(Post $post)
    {
        if (!$post->is_published) abort(404);
        $post->increment('view_count');
        $related  = Post::where('is_published',true)->where('id','!=',$post->id)
                        ->where('category',$post->category)->latest()->take(3)->get();
        $settings = \DB::table('admin_settings')->pluck('value','key');
        return view('website.blog-post', compact('post','related','settings'));
    }

    // ── AFFILIATE TRACKING ────────────────────
    public function trackAffiliate(Request $request, string $code)
    {
        $code = strtoupper($code);
        $affiliate = Affiliate::where('code', $code)->where('is_active', true)->first();
        if ($affiliate) {
            session(['affiliate_code' => $code]);
        }
        return redirect()->route('home');
    }

}