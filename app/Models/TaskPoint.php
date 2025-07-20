<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Use File facade for direct deletion

class TaskPoint extends Model
{
    // The disk configured in filesystems.php for storing images
    // const IMAGE_DISK = 'public_courses'; // Keep if using Storage facade

    protected $fillable = [
        'task_id',
        'title',
        'notes',
        'code_block',
        'image_path', // Stores filename relative to public/courses
        'points',
    ];

    protected $casts = [
        'points' => 'array',
    ];

    /** Get the task this point belongs to. */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Accessor for the full public image URL.
     * Uses asset() helper assuming images are in public/courses.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return asset('courses/' . $this->image_path);
        }
        return null;
    }

    /** Handle deleting associated image file when model is deleted. */
    protected static function booted(): void
    {
        static::deleting(function (TaskPoint $taskPoint) {
            if ($taskPoint->image_path) {
                // Use File facade for direct deletion from public path
                $filePath = public_path('courses/' . $taskPoint->image_path);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        });
    }
}