<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'note', 'price', 'weight', 'sort_order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function getLabelAttribute(): string
    {
        return $this->note ? "{$this->name} ({$this->note})" : $this->name;
    }

    /** Cân nặng (gram) của khổ có tên $name; null nếu không tìm thấy hoặc chưa có cột. */
    public static function weightForName(?string $name): ?int
    {
        if (!$name) return null;
        try {
            $bare = trim(preg_replace('/\s*\(.*\)\s*$/u', '', $name));
            $size = static::where('name', $name)->orWhere('name', $bare)->first();
            return $size ? ((int) ($size->getAttribute('weight') ?? 0) ?: null) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getDisplayPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }
}
