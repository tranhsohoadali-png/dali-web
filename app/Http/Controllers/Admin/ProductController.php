<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('sort_order')->orderBy('created_at', 'desc');
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products   = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $sizes      = Size::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.form', ['product' => null, 'categories' => $categories, 'sizes' => $sizes]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:200',
            'description'  => 'nullable|string',
            'size'         => 'nullable|string|max:50',
            'colors_count' => 'nullable|integer|min:1',
            'price'        => 'nullable|integer|min:0',
            'sale_price'   => 'nullable|integer|min:0',
            'badge'        => 'nullable|string|max:30',
            'badge_type'   => 'nullable|in:default,new,hot,sale',
            'sort_order'   => 'nullable|integer',
            'is_active'    => 'nullable|boolean',
            'main_image'   => 'nullable|image|max:5120',
            'size_ids'     => 'nullable|array',
            'size_ids.*'   => 'integer|exists:sizes,id',
        ]);
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }
        $data['size_ids']   = $request->input('size_ids', []);
        $data['price']      = $data['price'] ?? Size::whereIn('id', $data['size_ids'])->min('price') ?? 0;
        $data['slug']       = Str::slug($data['name']) . '-' . time();
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['badge_type'] = $data['badge_type'] ?? 'default';
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Đã thêm sản phẩm!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $sizes      = Size::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.form', compact('product', 'categories', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:200',
            'description'  => 'nullable|string',
            'size'         => 'nullable|string|max:50',
            'colors_count' => 'nullable|integer|min:1',
            'price'        => 'nullable|integer|min:0',
            'sale_price'   => 'nullable|integer|min:0',
            'badge'        => 'nullable|string|max:30',
            'badge_type'   => 'nullable|in:default,new,hot,sale',
            'sort_order'   => 'nullable|integer',
            'is_active'    => 'nullable|boolean',
            'main_image'   => 'nullable|image|max:5120',
            'size_ids'     => 'nullable|array',
            'size_ids.*'   => 'integer|exists:sizes,id',
        ]);
        if ($request->hasFile('main_image')) {
            if ($product->main_image) Storage::disk('public')->delete($product->main_image);
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }
        $data['size_ids']   = $request->input('size_ids', []);
        $data['price']      = $data['price'] ?? Size::whereIn('id', $data['size_ids'])->min('price') ?? 0;
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? $product->sort_order;
        $data['badge_type'] = $data['badge_type'] ?? 'default';
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm!');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image) Storage::disk('public')->delete($product->main_image);
        $product->delete();
        return back()->with('success', 'Đã xoá sản phẩm!');
    }
}
