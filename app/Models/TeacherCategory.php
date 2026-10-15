<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherCategory extends Model
{
    protected $fillable = ['name', 'slug', 'order'];
}
