<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function checkAllLessonsWatched($courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();
        
        $allWatched = true;
        foreach ($course->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $userLesson = $user->lessons->firstWhere('id', $lesson->id);
                if (!$userLesson || !$userLesson->pivot->watched) {
                    $allWatched = false;
                    break 2;
                }
            }
        }
        
        if ($allWatched) {
            // Mark course as completed
            $user->completedCourses()->syncWithoutDetaching([$courseId]);
        }
        
        return response()->json(['allWatched' => $allWatched]);
    }
} 