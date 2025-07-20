<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chapter; // Import the Chapter model
use App\Models\Question; // Import the Question model

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'time_limit',
        'passing_score',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
        
    }
    
}
