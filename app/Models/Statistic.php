<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable = [
        'user_id',
        'lesson_id',
        'watched',
        'time_spent',
    ];
}
