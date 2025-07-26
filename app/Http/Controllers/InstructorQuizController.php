<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class InstructorQuizController extends Controller
{
    public function showAttempts(Request $request)
    {
        $query = QuizAttempt::query()
            ->with(['user', 'quiz.chapter.course'])
            ->whereHas('quiz.chapter.course', function ($query) {
                $query->where('user_id', auth()->id());
            });

        // Filter by course if selected
        if ($request->has('course_id') && $request->course_id) {
            $query->whereHas('quiz.chapter.course', function ($query) use ($request) {
                $query->where('id', $request->course_id);
            });
        }

        // Get all courses for the filter dropdown
        $courses = Course::where('user_id', auth()->id())->get();

        // Get paginated attempts
        $attempts = $query->latest()->paginate(10);

        return view('instructor.quizzes.quiz_attempts', compact('attempts', 'courses'));
    }
} 