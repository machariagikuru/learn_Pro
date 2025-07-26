<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'short_video',
        'video_url',
        'duration',
        'price',
        'category_id',
        'user_id',
        'status',
        'why_choose_this_course'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses')
                    ->withPivot('enrollment_date', 'status')
                    ->withTimestamps();
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrolledUsers()
    {
        return $this->users();
    }

    public function lessons()
    {
        return $this->chapters->flatMap(function($chapter) {
            return $chapter->lessons;
        });
    }

    public function insightQuests()
    {
        return $this->hasMany(\App\Models\InsightQuest::class);
    }
}
