<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'description',
        'long_description',
        'video_url',
        'video',
        'order',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function course()
{
    return $this->belongsTo(Course::class);
}

public function users()
{
    return $this->belongsToMany(User::class)->withPivot('watched')->withTimestamps();
}


    
}
