<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id','customer_name','customer_phone','rating','content','image','order_code','is_approved'];
    protected $casts    = ['is_approved'=>'boolean'];

    public function product() { return $this->belongsTo(Product::class); }

    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
