<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function quiz()
    {
        return $this->hasMany(quiz::class);
    }
    public function quizzes()
    {
    return $this->hasMany(Quiz::class);
    }
    public function tasks()
    {
        
        return $this->hasMany(Task::class);
    }


}
