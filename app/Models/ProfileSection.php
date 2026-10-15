<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileSection extends Model
{
    protected $fillable = ['section_key', 'title', 'subtitle', 'content', 'image', 'principal_name', 'items', 'is_active'];
    protected $casts = [
        'is_active' => 'boolean',
        'items' => 'array',
    ];
}