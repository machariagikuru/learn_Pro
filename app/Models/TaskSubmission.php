<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'course_id',
        'notes',
        'status',
        'grade',
        'feedback'
    ];

    public function files(): HasMany
    {
        return $this->hasMany(SubmissionFile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
} 