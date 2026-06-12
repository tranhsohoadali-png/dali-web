<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** SĐT khách lưu bản thiết kế (trang /thiet-ke) — xem ở Admin → Khách thiết kế. */
class DesignLead extends Model
{
    protected $fillable = ['phone', 'device_id', 'original_url', 'enhanced_url', 'result_url'];
}
