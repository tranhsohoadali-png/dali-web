<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::latest();
        if ($request->filled('category')) $query->where('category', $request->category);
        $posts = $query->paginate(15)->withQueryString();
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'category'         => 'required|string|max:50',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'sort_order'       => 'nullable|integer',
            'is_published'     => 'nullable|boolean',
            'cover_image'      => 'nullable|image|max:5120',
        ]);
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }
        $data['slug']         = Str::slug($data['title']) . '-' . time();
        $data['is_published'] = $request->boolean('is_published', false);
        $data['sort_order']   = $data['sort_order'] ?? 0;
        if ($data['is_published']) $data['published_at'] = now();
        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Đã thêm bài viết!');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'category'         => 'required|string|max:50',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'sort_order'       => 'nullable|integer',
            'is_published'     => 'nullable|boolean',
            'cover_image'      => 'nullable|image|max:5120',
        ]);
        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }
        $data['is_published'] = $request->boolean('is_published', false);
        $data['sort_order']   = $data['sort_order'] ?? 0;
        if ($data['is_published'] && !$post->published_at) $data['published_at'] = now();
        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Đã cập nhật bài viết!');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
        $post->delete();
        return back()->with('success', 'Đã xoá bài viết!');
    }
}
