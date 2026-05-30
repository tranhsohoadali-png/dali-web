<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'note', 'price', 'sort_order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function getLabelAttribute(): string
    {
        return $this->note ? "{$this->name} ({$this->note})" : $this->name;
    }

    public function getDisplayPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }
}
