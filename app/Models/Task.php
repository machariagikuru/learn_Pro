<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'chapter_id',
        'title',
        'description',
        'videos_required_watched',
        'due_date'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /** Get the points (structured blocks) associated with the task. */
    public function taskPoints(): HasMany
    {
        return $this->hasMany(TaskPoint::class);
    }

    // Relationship with TaskSubmissions
    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    // Cleanup handled by TaskPoint::booted + DB Cascade (if used)
}