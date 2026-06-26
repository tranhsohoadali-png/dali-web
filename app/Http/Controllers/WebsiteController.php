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
        return view('website.index', $this->homeData());
    }

    /** Trang chủ bản mới (xem thử trước khi chốt) — cùng dữ liệu, giao diện mới. */
    public function indexV2()
    {
        return view('website.index-v2', $this->homeData());
    }

    /** Dữ liệu dùng chung cho trang chủ (cũ + bản xem thử). */
    private function homeData(): array
    {
        $hero       = HeroSection::first();
        $categories = Category::where('is_active',true)->withCount(['products'])->orderBy('sort_order')->get();
        // Ẩn sản phẩm lẻ của danh mục "chỉ bán dạng tổng hợp" khỏi trang chủ
        $products   = Product::with('category')->where('is_active',true)
            ->whereHas('category', fn($q) => $q->where('combo_only', false))
            ->orderBy('sort_order')->orderBy('sold_count','desc')->get();
        $settings   = DB::table('admin_settings')->pluck('value','key');

        // "Bán chạy nhất": mỗi danh mục góp 1 sản phẩm mua nhiều nhất, rồi đổ thêm cho đủ 8
        $bestSellers = collect();
        foreach ($categories as $cat) {
            if ($cat->combo_only) continue;   // danh mục chỉ bán combo -> không góp tranh lẻ
            $top = Product::with('category')->where('is_active',true)
                ->where('category_id',$cat->id)
                ->orderByDesc('sold_count')->orderBy('sort_order')->first();
            if ($top) $bestSellers->push($top);
        }
        // Nổi bật nhất (bán nhiều nhất) lên trước
        $bestSellers = $bestSellers->sortByDesc('sold_count')->values();
        // Bù thêm sản phẩm bán chạy còn lại nếu chưa đủ 8
        if ($bestSellers->count() < 8) {
            $fill = Product::with('category')->where('is_active',true)
                ->whereHas('category', fn($q) => $q->where('combo_only', false))
                ->whereNotIn('id', $bestSellers->pluck('id')->all())
                ->orderByDesc('sold_count')->orderBy('sort_order')
                ->take(8 - $bestSellers->count())->get();
            $bestSellers = $bestSellers->concat($fill);
        }
        $bestSellers = $bestSellers->take(8)->values();

        // Bài viết mới nhất cho khu "Cảm hứng & Hướng dẫn" ở trang chủ
        $latestPosts = \App\Models\Post::where('is_published', true)->latest('published_at')->take(3)->get();

        return compact('hero','categories','products','bestSellers','settings','latestPosts');
    }

    public function products(Request $request)
    {
        // Nếu lọc theo danh mục "chỉ bán combo" -> chuyển thẳng sang trang combo
        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)->first();
            if ($cat && $cat->combo_only) {
                return redirect()->route('category', $cat->slug);
            }
        }

        $query = Product::with('category')->where('is_active',true)
            // Ẩn sản phẩm lẻ của các danh mục "chỉ bán dạng tổng hợp"
            ->whereHas('category', fn($q) => $q->where('combo_only', false));
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
        // Số tranh mỗi trang: cho khách chọn 20 / 50 / 100 (mặc định 20)
        $perPage = (int) $request->input('per_page', 20);
        if (!in_array($perPage, [20, 50, 100], true)) $perPage = 20;
        $products   = $query->paginate($perPage)->withQueryString();
        $categories = Category::where('is_active',true)->orderBy('sort_order')->get();
        $settings   = DB::table('admin_settings')->pluck('value','key');
        return view('website.products', compact('products','categories','settings'));
    }

    public function categoryCombo(Category $category)
    {
        if (!$category->is_active) abort(404);
        // Tranh bán chạy lên đầu, tranh chưa bán xuống cuối (sort_order chỉ là tiêu chí phụ)
        $products = Product::where('category_id', $category->id)->where('is_active', true)
            ->orderByDesc('sold_count')->orderBy('sort_order')->get();
        $sizes    = \App\Models\Size::where('is_active', true)->orderBy('sort_order')->get();
        $settings = DB::table('admin_settings')->pluck('value','key');

        // Tổng hợp đánh giá từ tất cả sản phẩm trong danh mục
        $productIds   = $products->pluck('id');
        $reviewStats  = \App\Models\Review::whereIn('product_id', $productIds)
            ->where('is_approved', true)
            ->selectRaw('COUNT(*) as total, AVG(rating) as avg')
            ->first();
        $reviewCount  = (int) ($reviewStats->total ?? 0);
        $avgRating    = $reviewCount > 0 ? round((float)$reviewStats->avg, 1) : 5.0;

        // Danh sách đánh giá đã duyệt của các tranh trong danh mục (mới nhất trước)
        $reviews = \App\Models\Review::with('product')
            ->whereIn('product_id', $productIds)
            ->where('is_approved', true)
            ->latest()
            ->take(40)
            ->get();

        return view('website.category-combo', compact('category','products','sizes','settings','reviewCount','avgRating','reviews'));
    }

    public function product(Product $product, \Illuminate\Http\Request $request)
    {
        if (!$product->is_active) abort(404);
        // Danh mục "chỉ bán dạng tổng hợp" -> không có trang tranh lẻ, chuyển sang combo
        if ($product->category && $product->category->combo_only) {
            return redirect()->route('category', $product->category->slug);
        }
        // Nếu URL có ?ref=CODE → lưu affiliate code vào session/cookie ngay
        $refCode = strtoupper(trim($request->input('ref', '')));
        if ($refCode) {
            $aff = Affiliate::where('code', $refCode)->where('is_active', true)->first();
            if ($aff) {
                session(['affiliate_code' => $refCode]);
                cookie()->queue('affiliate_code', $refCode, 60 * 24 * 30);
            }
        }
        $related  = Product::where('category_id',$product->category_id)->where('id','!=',$product->id)->where('is_active',true)->take(4)->get();
        $settings = DB::table('admin_settings')->pluck('value','key');
        return view('website.product', compact('product','related','settings'));
    }

    public function trackOrder(Request $request)
    {
        $order  = null;
        $orders = null; // nhiều đơn khi tìm bằng SĐT

        if ($request->filled('code')) {
            $q = strtoupper(trim($request->code));
            // Tìm theo mã đơn
            $order = Order::with('items.product')->where('code', $q)->first();
            // Nếu không thấy mã đơn, thử tìm theo SĐT
            if (!$order) {
                $phone = preg_replace('/\D/', '', $q);
                if (strlen($phone) >= 9) {
                    $orders = Order::with('items')
                        ->where('customer_phone', 'like', '%'.$phone.'%')
                        ->latest()->take(20)->get();
                    if ($orders->count() === 1) {
                        $order  = $orders->first()->load('items.product');
                        $orders = null;
                    } elseif ($orders->isEmpty()) {
                        $orders = null;
                    }
                }
            }
        }

        $settings = \DB::table('admin_settings')->pluck('value','key');
        return view('website.order-tracking', compact('order','orders','settings'));
    }

    /** Trang hướng dẫn tô tranh: 6 bước + video YouTube + bài viết blog (đã gộp). */
    public function guide()
    {
        $settings = \DB::table('admin_settings')->pluck('value','key');
        $posts    = Post::where('is_published', true)->latest('published_at')->take(6)->get();
        return view('website.guide', compact('settings','posts'));
    }

    /**
     * Tính phí giao hàng: miễn phí theo ngưỡng, nếu không thì lấy cước thật
     * từ Viettel Post (getPrice); thất bại/tắt → phí cố định trong cài đặt.
     * @return array [int $fee, int $weight]
     */
    private function shipFeeFor($settings, int $afterDisc, ?string $city, ?string $address, string $payment, int $qty, ?int $weightOverride = null): array
    {
        $freeFrom = (int)($settings['free_ship_from'] ?? 299000);
        // Ưu tiên cân nặng tính theo từng kích thước (weightOverride); nếu không có → mức mặc định × số lượng.
        $weight   = max(1, $weightOverride ?? ((int)($settings['default_weight'] ?? 500) * max(1, $qty)));
        if ($afterDisc >= $freeFrom) return [0, $weight];

        if ($city && $address) {
            try {
                $vtp = app(\App\Services\ViettelPostService::class);
                if ($vtp->enabled()) {
                    $cod  = $payment === 'COD' ? $afterDisc : 0;
                    $data = $vtp->getPrice(trim($address . ', ' . $city, ', '), $weight, $afterDisc, $cod);
                    $fee  = (int)($data['MONEY_TOTAL'] ?? 0);
                    if ($fee > 0) return [$fee, $weight];
                }
            } catch (\Throwable $e) {
                \Log::warning('VTP getPrice fail: ' . $e->getMessage());
            }
        }
        return [(int)($settings['ship_fee'] ?? 30000), $weight];
    }

    /**
     * Tổng cân nặng (gram) của giỏ hàng, cộng theo cân nặng từng kích thước.
     * @param array $items mỗi phần tử có 'quantity' và ('size_id' hoặc 'size_name')
     */
    private function cartWeight(array $items, $settings): int
    {
        $default = (int)($settings['default_weight'] ?? 500);
        $total = 0;
        foreach ($items as $it) {
            $qty = max(1, (int)($it['quantity'] ?? 1));
            $w = null;
            if (!empty($it['size_id'])) {
                try {
                    $w = optional(\App\Models\Size::find($it['size_id']))->getAttribute('weight');
                } catch (\Throwable $e) { $w = null; }
            }
            if ($w === null && !empty($it['size_name'])) {
                $w = \App\Models\Size::weightForName($it['size_name']);
            }
            $total += (int)($w ?? $default) * $qty;
        }
        return max(1, $total);
    }

    /** API xem trước phí giao hàng cho trang checkout. */
    public function calcShip(Request $request)
    {
        $settings  = \DB::table('admin_settings')->pluck('value','key');
        $afterDisc = max(0, (int)$request->input('amount', 0));
        $qty       = (int)$request->input('qty', 1);
        $estWeight = $this->cartWeight([['quantity' => $qty, 'size_id' => $request->input('size_id'), 'size_name' => $request->input('size_name')]], $settings);
        [$fee, $weight] = $this->shipFeeFor(
            $settings, $afterDisc,
            $request->input('city'), $request->input('address'),
            $request->input('payment', 'COD'), $qty, $estWeight
        );
        return response()->json(['fee' => $fee, 'free' => $fee === 0, 'total' => $afterDisc + $fee]);
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

        // Giá LẤY TỪ DB theo sản phẩm + kích thước (chống giả mạo giá phía client).
        // Đơn catalog luôn có product_id; chỉ fallback giá client khi không xác định được SP.
        $qty   = $data['quantity'];
        $price = max(0, (int) $data['price']);
        if (!empty($data['product_id'])) {
            $prod = Product::find($data['product_id']);
            if ($prod) {
                $sizeId = (int) $request->input('size_id', 0);
                $size   = $sizeId ? \App\Models\Size::find($sizeId) : null;
                $price  = $size ? (int) $size->price : (int) $prod->price;
            }
        }
        $sub = $price * $qty;

        // Handle coupon (dựa trên tiền hàng đã tính từ DB)
        $couponCode     = strtoupper(trim($request->input('coupon_code', '')));
        $couponDiscount = 0;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                [$ok] = $coupon->isValid($sub);
                if ($ok) {
                    $couponDiscount = $coupon->calcDiscount($sub);
                    $coupon->increment('used_count');
                }
            }
        }
        $settings   = \DB::table('admin_settings')->pluck('value','key');
        $discPct    = (int)($settings['discount_bank'] ?? 5);
        $discount   = $data['payment'] === 'BANK' ? (int)round($sub * $discPct / 100) : 0;
        $discount  += $couponDiscount; // add coupon discount
        $after    = $sub - $discount;
        $orderWeight = $this->cartWeight([['quantity' => $qty, 'size_id' => $request->input('size_id'), 'size_name' => $data['product_size'] ?? null]], $settings);
        [$ship, $weight] = $this->shipFeeFor($settings, $after, $data['customer_city'], $data['customer_addr'], $data['payment'], $qty, $orderWeight);
        $total    = $after + $ship;
        $affCode  = $this->getAffiliateCode($request);
        $code     = 'DALI-' . substr(time(), -6);

        $order = Order::create([
            'code'                 => $code,
            'customer_name'        => $data['customer_name'],
            'customer_phone'       => $data['customer_phone'],
            'customer_city'        => $data['customer_city'],
            'customer_address'     => $data['customer_addr'],
            'note'                 => $data['note'] ?? '',
            'coupon_code'          => $couponCode ?: null,
            'coupon_discount'      => $couponDiscount,
            'affiliate_code'       => $affCode ?: null,
            'affiliate_commission' => 0,
            'payment_method'       => $data['payment'],
            'payment_status'       => 'pending',
            'status'               => 'new',
            'subtotal'             => $sub,
            'discount'             => $discount,
            'ship_fee'             => $ship,
            'total'                => $total,
            'weight'               => $weight,
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

        // Ghi hoa hồng CTV
        $this->recordAffiliateCommission($order, $affCode, $total);

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
        $posts      = \App\Models\Post::where('is_published', true)->get(['slug','updated_at']);
        return response()->view('website.sitemap', compact('products','categories','posts'))
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
        $sizeId    = (int) $request->input('size_id', 0);
        $product   = Product::find($productId);
        if (!$product) {
            return response()->json(['success'=>false,'message'=>'Sản phẩm không tồn tại']);
        }

        // Lấy giá + tên theo kích thước đã chọn (từ bảng giá chung)
        $size = $sizeId ? \App\Models\Size::find($sizeId) : null;
        if (!$size) {
            // fallback: kích thước nhỏ nhất của sản phẩm
            $size = $product->sizes()->first();
        }
        $price     = $size ? (int) $size->price : (int) $product->price;
        $sizeLabel = $size ? $size->label : ($product->size ?? '');
        $sizeKey   = $size ? $size->id : 0;

        $cart = session('cart', []);
        $key  = 'p' . $productId . '_s' . $sizeKey;
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'size_id'    => $sizeKey,
                'name'       => $product->name,
                'size'       => $sizeLabel,
                'price'      => $price,
                'main_image' => $product->main_image,
                'quantity'   => $qty,
            ];
        }
        session(['cart' => $cart]);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['success'=>true,'count'=>$count,'message'=>'Đã thêm vào giỏ hàng!']);
    }

    public function updateCart(Request $request)
    {
        $key  = (string) $request->input('key');
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
        $key  = (string) $request->input('key');
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

        // Affiliate (session + cookie 30 ngày)
        $affCode = $this->getAffiliateCode($request);

        $afterDisc = $subtotal - $discount - $couponDiscount;
        $cartQty   = (int) array_sum(array_column($cart, 'quantity'));
        $cartItems = array_map(fn($i) => ['quantity' => $i['quantity'], 'size_id' => $i['size_id'] ?? null, 'size_name' => $i['size'] ?? null], $cart);
        $orderWeight = $this->cartWeight($cartItems, $settings);
        [$shipFee, $weight] = $this->shipFeeFor($settings, $afterDisc, $request->input('customer_city'), $request->input('customer_addr'), $request->input('payment','COD'), $cartQty, $orderWeight);
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
            'weight'            => $weight,
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

        // Ghi hoa hồng CTV (dùng helper chung) — lấy số tiền hoa hồng để hiện trong thông báo
        $affCommission = $this->recordAffiliateCommission($order, $affCode, $total);

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
            // Cookie 30 ngày — bảo lưu kể cả khi đóng browser
            cookie()->queue('affiliate_code', $code, 60 * 24 * 30);
        }
        return redirect()->route('home');
    }

    // ── HÀM HELPER: lấy affiliate code từ session hoặc cookie ──
    private function getAffiliateCode(Request $request): string
    {
        return strtoupper(trim(
            $request->input('affiliate_code', '')
            ?: session('affiliate_code', '')
            ?: $request->cookie('affiliate_code', '')
        ));
    }

    // ── HÀM HELPER: ghi hoa hồng cho affiliate ──
    private function recordAffiliateCommission(Order $order, string $affCode, int $total): int
    {
        if (!$affCode) return 0;
        $affiliate = Affiliate::where('code', $affCode)->where('is_active', true)->first();
        if (!$affiliate) return 0;
        // Đại lý (agent) ăn chênh lệch giá sỉ -> KHÔNG hưởng hoa hồng %; chỉ gắn mã để theo dõi.
        if ($affiliate->isAgent()) {
            $order->update(['affiliate_code' => $affCode]);
            return 0;
        }
        $commission = (int) round($total * $affiliate->commission_rate / 100);
        $order->update([
            'affiliate_code'       => $affCode,
            'affiliate_commission' => $commission,
        ]);
        $affiliate->increment('total_earned', $commission);
        $affiliate->increment('total_orders');
        return $commission;
    }

}