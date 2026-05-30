<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title','slug','excerpt','content','cover_image',
        'category','meta_title','meta_description',
        'is_published','view_count','sort_order','published_at',
    ];
    protected $casts = [
        'is_published'  => 'boolean',
        'published_at'  => 'datetime',
    ];

    public function getReadTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content ?? ''));
        return max(1, (int) round($words / 200));
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($p) {
            if (empty($p->slug)) {
                $p->slug = Str::slug($p->title) . '-' . time();
            }
            if ($p->is_published && !$p->published_at) {
                $p->published_at = now();
            }
        });
        static::updating(function ($p) {
            if ($p->is_published && !$p->published_at) {
                $p->published_at = now();
            }
        });
    }
}
