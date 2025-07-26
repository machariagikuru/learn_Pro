<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

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
}
