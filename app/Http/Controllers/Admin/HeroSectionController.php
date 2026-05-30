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
            'float_image' => 'nullable|image',
            'tag_text' => 'nullable|string|max:255',
            'tag_subtext' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('main_image')) {

            $mainImage = $request->file('main_image')->store('hero', 'public');

            $hero->main_image = $mainImage;
        }

        if ($request->hasFile('float_image')) {

            $floatImage = $request->file('float_image')->store('hero', 'public');

            $hero->float_image = $floatImage;
        }

        $hero->tag_text = $request->tag_text;
        $hero->tag_subtext = $request->tag_subtext;

        $hero->save();

        return back()->with('success', 'Cập nhật thành công');
    }
}