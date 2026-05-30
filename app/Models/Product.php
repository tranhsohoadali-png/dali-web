<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'size', 'size_ids', 'colors_count', 'price', 'sale_price',
        'badge', 'badge_type', 'main_image',
        'is_active', 'sort_order', 'sold_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'size_ids'  => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Các kích thước (Size) mà sản phẩm này có, lấy giá từ bảng giá chung.
     */
    public function sizes()
    {
        $ids = $this->size_ids ?: [];
        if (empty($ids)) return collect();
        return Size::whereIn('id', $ids)->where('is_active', true)
                   ->orderBy('sort_order')->get();
    }

    /** Giá nhỏ nhất trong các size đang bật (để hiển thị "Từ ..."). */
    public function getPriceFromAttribute(): int
    {
        $sizes = $this->sizes();
        if ($sizes->isNotEmpty()) return (int) $sizes->min('price');
        return (int) $this->price; // fallback nếu chưa gán size
    }

    /** Có nhiều hơn 1 size không (để hiển thị chữ "Từ"). */
    public function getHasMultipleSizesAttribute(): bool
    {
        return $this->sizes()->count() > 1;
    }

    public function getDisplayPriceAttribute(): string
    {
        return number_format($this->price_from, 0, ',', '.') . 'đ';
    }

    public function getDisplaySalePriceAttribute(): ?string
    {
        return $this->sale_price
            ? number_format($this->sale_price, 0, ',', '.') . 'đ'
            : null;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->sale_price && $this->price > 0) {
            return round((1 - $this->sale_price / $this->price) * 100);
        }
        return null;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($p) {
            if (empty($p->slug)) {
                $p->slug = Str::slug($p->name) . '-' . uniqid();
            }
        });
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->orderByDesc('created_at');
    }

    public function allReviews()
    {
        return $this->hasMany(Review::class)->orderByDesc('created_at');
    }

    public function getAvgRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 5, 1);
    }
}
