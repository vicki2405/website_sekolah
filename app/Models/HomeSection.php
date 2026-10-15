<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = ['section_key', 'title', 'subtitle', 'content', 'is_active'];
    protected $casts = [
        'is_active' => 'boolean',
        'content' => 'json'
    ];
}
