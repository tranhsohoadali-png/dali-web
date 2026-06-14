<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DesignLead;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
