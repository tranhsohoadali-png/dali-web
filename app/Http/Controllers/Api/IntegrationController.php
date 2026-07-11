<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DesignLead;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * API tích hợp cho app marketing (social-suite).
 * - products : lấy danh sách sản phẩm để làm nội dung.
 * - leads    : lấy dữ liệu khách (SĐT thiết kế + đơn hàng) để remarketing.
 * - posts    : nhận bài viết từ app để đăng lên blog.
 * Mọi route đều qua middleware integration.auth.
 */
class IntegrationController extends Controller
{
    public function ping()
    {
        return response()->json([
            'ok'   => true,
            'site' => 'dali-web',
            'time' => now()->toIso8601String(),
        ]);
    }

    /** Danh sách sản phẩm đang bán (cho bộ máy nội dung). */
    public function products()
    {
        $items = Product::with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function (Product $p) {
                return [
                    'id'               => $p->id,
                    'name'             => $p->name,
                    'slug'             => $p->slug,
                    'category'         => optional($p->category)->name,
                    'description'      => $p->description,
                    'size'             => $p->size,
                    'colors_count'     => $p->colors_count,
                    'price'            => (int) $p->price,
                    'sale_price'       => $p->sale_price ? (int) $p->sale_price : null,
                    'price_from'       => $p->price_from,
                    'discount_percent' => $p->discount_percent,
                    'sold_count'       => $p->sold_count,
                    'image_url'        => $p->main_image ? asset('storage/' . $p->main_image) : null,
                    'url'              => url('/san-pham/' . $p->slug),
                ];
            });

        return response()->json(['ok' => true, 'count' => $items->count(), 'products' => $items]);
    }

    /** Dữ liệu khách: SĐT để lại ở trang thiết kế + đơn hàng (để remarketing). */
    public function leads(Request $r)
    {
        $since = $r->query('since');
        // Ngày sai định dạng -> mặc định 60 ngày, không để 500.
        $sinceTs = $since ? rescue(fn () => Carbon::parse($since), now()->subDays(60), false) : now()->subDays(60);
        $limit = min(max((int) $r->query('limit', 1000), 1), 5000);

        $designs = DesignLead::where('created_at', '>=', $sinceTs)
            ->orderByDesc('created_at')->limit($limit)->get()
            ->map(fn ($l) => [
                'source'     => 'design',
                'phone'      => $l->phone,
                'created_at' => optional($l->created_at)->toIso8601String(),
            ]);

        $orders = Order::where('created_at', '>=', $sinceTs)
            ->orderByDesc('created_at')->limit($limit)->get()
            ->map(fn ($o) => [
                'source'     => 'order',
                'code'       => $o->code,
                'name'       => $o->customer_name,
                'phone'      => $o->customer_phone,
                'city'       => $o->customer_city,
                'address'    => $o->customer_address,
                'total'      => (int) $o->total,
                'status'     => $o->status,
                'created_at' => optional($o->created_at)->toIso8601String(),
            ]);

        return response()->json([
            'ok'      => true,
            'designs' => $designs,
            'orders'  => $orders,
        ]);
    }

    /** Danh mục + bảng size (kèm giá) để app dựng form đăng sản phẩm. */
    public function catalogMeta()
    {
        return response()->json([
            'ok'         => true,
            // Chỉ danh mục đang hiển thị + không phải danh mục combo (combo không có trang sản phẩm lẻ)
            'categories' => Category::where('is_active', true)->where('combo_only', false)
                ->orderBy('name')->get(['id', 'name']),
            'sizes'      => Size::where('is_active', true)->orderBy('sort_order')
                ->get(['id', 'name', 'note', 'price']),
        ]);
    }

    /**
     * Nhận SẢN PHẨM MỚI từ app marketing. Luôn tạo ở trạng thái ẨN (is_active=false)
     * để chủ shop duyệt trong Admin rồi mới bật bán — app không thể tự lên sóng.
     */
    public function createProduct(Request $r)
    {
        $data = $r->validate([
            'name'         => 'required|string|max:150',
            'description'  => 'required|string|max:5000',
            // LƯU Ý: Rule::exists()->where(..., false) serialize PHP false thành CHUỖI RỖNG
            // ("combo_only,\"\"") → so với 0 trong DB luôn fail. Phải dùng số 1/0 tường minh.
            'category_id'  => [
                'required', 'integer',
                \Illuminate\Validation\Rule::exists('categories', 'id')
                    ->where('is_active', 1)->where('combo_only', 0),
            ],
            'size_ids'     => 'required|array|min:1',
            'size_ids.*'   => [
                'integer',
                \Illuminate\Validation\Rule::exists('sizes', 'id')->where('is_active', 1),
            ],
            'price'        => 'nullable|integer|min:1000',
            'sale_price'   => 'nullable|integer|min:1000',
            'colors_count' => 'nullable|integer|min:1|max:99',
            'badge'        => 'nullable|string|max:30',
            'image_url'    => 'required|url|max:500',
        ]);

        // Chống SSRF: chỉ tải ảnh từ chính hệ mình (app marketing / web)
        $host = parse_url($data['image_url'], PHP_URL_HOST) ?: '';
        $allowed = ['agent.tranhdali.vn', 'tranhdali.vn', 'www.tranhdali.vn'];
        if (! in_array(strtolower($host), $allowed, true)) {
            return response()->json(['ok' => false, 'error' => 'image_url phải thuộc agent.tranhdali.vn hoặc tranhdali.vn'], 422);
        }

        // Giá cuối = giá gửi lên, mặc định giá size rẻ nhất (đúng cách web hiển thị "Từ ...")
        $minSize = Size::whereIn('id', $data['size_ids'])->where('is_active', true)->min('price');
        $finalPrice = (int) ($data['price'] ?? $minSize);
        if (! empty($data['sale_price']) && (int) $data['sale_price'] >= $finalPrice) {
            return response()->json([
                'ok' => false,
                'error' => 'Giá khuyến mãi phải NHỎ HƠN giá gốc (' . number_format($finalPrice, 0, ',', '.') . 'đ)',
            ], 422);
        }

        // Tải ảnh chính về storage (như admin upload tay).
        // KHÔNG theo redirect (chặn lách whitelist SSRF) + đọc theo khúc, cắt ngay khi quá 8MB.
        try {
            $resp = Http::timeout(30)->withoutRedirecting()
                ->withOptions(['stream' => true])->get($data['image_url']);
            if ($resp->status() >= 300 && $resp->status() < 400) {
                return response()->json(['ok' => false, 'error' => 'image_url không được redirect'], 422);
            }
            if (! $resp->successful()) {
                return response()->json(['ok' => false, 'error' => 'Không tải được ảnh (' . $resp->status() . ')'], 422);
            }
            $mime = strtolower((string) $resp->header('Content-Type'));
            if (! str_starts_with($mime, 'image/')) {
                return response()->json(['ok' => false, 'error' => 'URL không phải ảnh (' . Str::limit($mime, 60) . ')'], 422);
            }
            $stream = $resp->toPsrResponse()->getBody();
            $body = '';
            $cap = 8 * 1024 * 1024;
            while (! $stream->eof()) {
                $body .= $stream->read(64 * 1024);
                if (strlen($body) > $cap) {
                    return response()->json(['ok' => false, 'error' => 'Ảnh quá 8MB'], 422);
                }
            }
            if ($body === '') {
                return response()->json(['ok' => false, 'error' => 'Ảnh rỗng'], 422);
            }
            $ext = str_contains($mime, 'png') ? 'png' : (str_contains($mime, 'webp') ? 'webp' : (str_contains($mime, 'gif') ? 'gif' : 'jpg'));
            $path = 'products/' . uniqid('agent-') . '.' . $ext;
            Storage::disk('public')->put($path, $body);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'error' => 'Lỗi tải ảnh: ' . Str::limit($e->getMessage(), 120)], 422);
        }

        try {
            $product = Product::create([
                'category_id'  => $data['category_id'],
                'name'         => $data['name'],
                'description'  => $data['description'],
                'size_ids'     => array_values(array_unique(array_map('intval', $data['size_ids']))),
                'colors_count' => $data['colors_count'] ?? 36, // khớp DEFAULT của cột (NOT NULL)
                'price'        => $finalPrice,
                'sale_price'   => $data['sale_price'] ?? null,
                'badge'        => $data['badge'] ?? 'Mới',
                'badge_type'   => 'new',
                'main_image'   => $path,
                'is_active'    => false, // LUÔN ẩn chờ duyệt
                'sort_order'   => 0,
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path); // không bỏ ảnh mồ côi khi tạo lỗi
            return response()->json(['ok' => false, 'error' => 'Không tạo được sản phẩm: ' . Str::limit($e->getMessage(), 120)], 500);
        }

        return response()->json([
            'ok'        => true,
            'id'        => $product->id,
            'slug'      => $product->slug,
            'is_active' => false,
            'url'       => url('/san-pham/' . $product->slug),
            'admin_url' => url('/admin/products/' . $product->id . '/edit'),
        ]);
    }

    /** Nhận bài viết từ app -> tạo bài blog (mặc định lưu NHÁP để duyệt). */
    public function createPost(Request $r)
    {
        $data = $r->validate([
            'title'            => 'required|string|max:200',
            'excerpt'          => 'nullable|string|max:255',
            'content'          => 'required|string',
            'category'         => 'nullable|string|max:50',
            'cover_image'      => 'nullable|string|max:255',
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:255',
            'publish'          => 'nullable|boolean',
        ]);

        $post = Post::create([
            'title'            => $data['title'],
            'excerpt'          => $data['excerpt'] ?? Str::limit(trim(strip_tags($data['content'])), 150),
            'content'          => $data['content'],
            'category'         => $data['category'] ?? 'Tin tức',
            'cover_image'      => $data['cover_image'] ?? null,
            'meta_title'       => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? null,
            'is_published'     => $r->boolean('publish'),
        ]);

        return response()->json([
            'ok'           => true,
            'id'           => $post->id,
            'slug'         => $post->slug,
            'is_published' => $post->is_published,
            'url'          => url('/blog/' . $post->slug),
            'admin_url'    => url('/admin/posts/' . $post->id . '/edit'),
        ]);
    }
}
