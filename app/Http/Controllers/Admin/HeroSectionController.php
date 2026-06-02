<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeroSection;

class HeroSectionController extends Controller
{
    public function edit()
    {
        $hero = HeroSection::first();

        if (!$hero) {
            $hero = HeroSection::create([]);
        }

        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request)
    {
        $hero = HeroSection::first();

        $request->validate([
            'main_image' => 'nullable|image',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:5120',
            'tag_text' => 'nullable|string|max:255',
            'tag_subtext' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('main_image')) {
            $hero->main_image = $request->file('main_image')->store('hero', 'public');
        }

        // Ảnh slideshow hiện có
        $gallery = $hero->gallery ?? [];

        // Xoá các ảnh được tích chọn
        $remove = (array) $request->input('remove_gallery', []);
        if ($remove) {
            foreach ($remove as $path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            $gallery = array_values(array_filter($gallery, fn($p) => !in_array($p, $remove, true)));
        }

        // Thêm ảnh slideshow mới
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('hero', 'public');
            }
        }

        $hero->gallery = array_values($gallery);
        $hero->tag_text = $request->tag_text;
        $hero->tag_subtext = $request->tag_subtext;

        $hero->save();

        return back()->with('success', 'Cập nhật thành công');
    }
}