<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsightQuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'image',
        'course_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(\App\Models\InsightQuestSubmission::class);
    }
} 