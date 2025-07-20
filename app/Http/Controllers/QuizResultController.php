<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;

class QuizResultController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request as needed
        $validated = $request->validate([
            'quiz_id'         => 'required|exists:quizzes,id',
            'total_questions' => 'required|integer',
            'correct_answers' => 'required|integer',
            'wrong_answers'   => 'required|integer',
            'unanswered'      => 'required|integer',
            'percentage'      => 'required|integer',
            'time_taken'      => 'required|integer',
        ]);

        // Optionally include the authenticated user's id
        $validated['user_id'] = Auth::id();

        // Create a new QuizResult record
        $result = QuizResult::create($validated);

        // Return response as JSON (for AJAX calls)
        return response()->json([
            'success' => true,
            'result'  => $result
        ]);
    }

    public function deletePrevious(Request $request, Quiz $quiz)
{
    try {
        QuizResult::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Previous quiz record deleted successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the previous record.'
        ], 500);
    }
}

}
