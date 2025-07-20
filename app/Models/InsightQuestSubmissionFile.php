<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsightQuestSubmissionFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'insight_quest_submission_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    /**
     * Optional: Get the submission that owns the file.
     */
    // public function submission()
    // {
    //     return $this->belongsTo(InsightQuestSubmission::class, 'insight_quest_submission_id');
    // }
}