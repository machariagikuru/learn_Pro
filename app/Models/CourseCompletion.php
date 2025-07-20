<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCompletion extends Model
{
    protected $table = 'course_completions';

    protected $fillable = [
        'user_id',
        'course_id',
    ];
} 