<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolOrgNode extends Model
{
    protected $fillable = [
        'parent_id',
        'title',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'school_org_node_teacher')
            ->withPivot(['label', 'order'])
            ->withTimestamps()
            ->orderByPivot('order');
    }
}
