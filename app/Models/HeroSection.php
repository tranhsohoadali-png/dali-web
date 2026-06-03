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

    /** Tất cả ảnh hero theo thứ tự: ảnh chính trước, rồi các ảnh slideshow.
     *  An toàn khi cột gallery chưa tồn tại trong DB (migration chưa chạy). */
    public function slideImages(): array
    {
        $imgs = [];
        if ($this->main_image) $imgs[] = $this->main_image;
        try {
            $gallery = $this->getAttribute('gallery');
            foreach ((is_array($gallery) ? $gallery : []) as $g) {
                if ($g) $imgs[] = $g;
            }
        } catch (\Throwable $e) {
            // cột gallery chưa có → bỏ qua, chỉ dùng main_image
        }
        return $imgs;
    }
}