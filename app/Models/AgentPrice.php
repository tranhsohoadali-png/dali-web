<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentPrice extends Model
{
    protected $fillable = ['affiliate_id', 'size_id', 'price'];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
