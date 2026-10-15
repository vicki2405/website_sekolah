<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tefa extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'hero_subtitle',
        'slug',
        'content',
        'background_image',
        'gallery',
        'order',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];
}
