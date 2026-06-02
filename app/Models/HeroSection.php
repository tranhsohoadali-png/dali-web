<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    protected $fillable = [
        'main_image',
        'gallery',
        'float_image',
        'tag_text',
        'tag_subtext',
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    /** Tất cả ảnh hero theo thứ tự: ảnh chính trước, rồi các ảnh slideshow. */
    public function slideImages(): array
    {
        $imgs = [];
        if ($this->main_image) $imgs[] = $this->main_image;
        foreach (($this->gallery ?? []) as $g) {
            if ($g) $imgs[] = $g;
        }
        return $imgs;
    }
}