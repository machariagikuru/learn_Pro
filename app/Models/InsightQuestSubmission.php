<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InsightQuest;
use App\Models\User;
use App\Models\InsightQuestSubmissionFile;

class InsightQuestSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'insight_quest_id',
        'user_id',
        'status',
        'grade',
        'feedback',
        'submitted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'submitted_at' => 'datetime',
        'grade' => 'float',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the quest that owns the submission.
     */
    public function insightQuest()
    {
        return $this->belongsTo(InsightQuest::class, 'insight_quest_id');
    }

    /**
     * Get the user that owns the submission.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the files associated with the submission.
     */
    public function files()
    {
        return $this->hasMany(InsightQuestSubmissionFile::class, 'insight_quest_submission_id');
    }

    /**
     * Scope a query to only include pending submissions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include reviewed submissions.
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Scope a query to only include submissions for a specific course.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->whereHas('insightQuest', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        });
    }

    /**
     * Check if the submission has been reviewed.
     */
    public function isReviewed()
    {
        return $this->status === 'reviewed';
    }

    /**
     * Check if the submission is pending review.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get the submission's grade as a percentage.
     */
    public function getGradePercentageAttribute()
    {
        return $this->grade ? number_format($this->grade, 1) . '%' : 'Not graded';
    }
}