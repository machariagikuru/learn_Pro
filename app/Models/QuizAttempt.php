<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $table = 'quiz_results';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'unanswered',
        'percentage',
        'time_taken',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
