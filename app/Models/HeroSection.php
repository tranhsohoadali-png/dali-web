<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    protected $fillable = [
        'main_image',
        'float_image',
        'tag_text',
        'tag_subtext',
    ];
}