<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    // لو جدولك اسمه questions فعلاً
    protected $table = 'questions';

    protected $fillable = [
        'quiz_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
    ];
    public function quiz()
{
    return $this->belongsTo(Quiz::class);
}

}
