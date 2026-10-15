<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name', 'subject', 'position', 'category', 'photo',
        'bio', 'email', 'phone', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function orgNodes()
    {
        return $this->belongsToMany(SchoolOrgNode::class, 'school_org_node_teacher')
            ->withPivot(['label', 'order'])
            ->withTimestamps()
            ->orderByPivot('order');
    }
}
