<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\Auth;

class QuizQuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = QuizQuestion::query();
        
        if ($request->has('quiz_id') && $request->quiz_id) {
            $query->where('quiz_id', $request->quiz_id);
        }
        
        // Filter by the current user's quizzes
        $query->whereHas('quiz.chapter.course', function ($q) {
            $q->where('user_id', Auth::id());
        });
        
        $quizQuestions = $query->paginate(10);
        $quizzes = Quiz::whereHas('chapter.course', function ($q) {
            $q->where('user_id', Auth::id());
        })->get(); // For the dropdown filter
        
        return view('instructor.quizquestions.view_quizquestions', compact('quizQuestions', 'quizzes'));
    }

    public function create()
    {
        $quizzes = Quiz::all();
        return view('instructor.quizquestions.add_quizquestions', compact('quizzes'));
    }

    public function store(Request $request)
    {
        // dd($request->all()); // هنا يتم تفريغ جميع البيانات المرسلة من الفورم
    
        $validated = $request->validate([
            'quiz_id'        => 'required|exists:quizzes,id',
            'question_text'  => 'required|string',
            'correct_option' => 'required|in:option_a,option_b,option_c,option_d',  // Add in clause if you need extra assurance
            'option_a'       => 'nullable|string',
            'option_b'       => 'nullable|string',
            'option_c'       => 'nullable|string',
            'option_d'       => 'nullable|string',
        ]);
        
    
        // Map the keys to match the actual DB columns:
        $data = [
            'quiz_id'        => $validated['quiz_id'],
            'question_text'  => $validated['question_text'],
            'correct_option' => $validated['correct_option'],
            'option_a'       => $validated['option_a'] ?? null,
            'option_b'       => $validated['option_b'] ?? null,
            'option_c'       => $validated['option_c'] ?? null,
            'option_d'       => $validated['option_d'] ?? null,
        ];
    
        QuizQuestion::create($data);
    
        return redirect()->route('quizquestions.view')
        ->with('success', 'Question Added successfully');
    }

    public function edit($id)
    {
        // Retrieve the quiz question by its ID
        $quizQuestion = QuizQuestion::findOrFail($id);

        // Ensure the quiz question belongs to the current user's quiz
        if ($quizQuestion->quiz->chapter->course->user_id !== Auth::id()) {
            return redirect()->route('quizquestions.view')->with('error', 'Unauthorized access.');
        }

        // Retrieve all quizzes to populate the dropdown
        $quizzes = Quiz::whereHas('chapter.course', function ($q) {
            $q->where('user_id', Auth::id());
        })->get();
        
        return view('instructor.quizquestions.update_quizquestions', compact('quizQuestion', 'quizzes'));
    }

public function update(Request $request, $id)
{
    // Validate incoming data
    $validated = $request->validate([
        'quiz_id'        => 'required|exists:quizzes,id',
        'question_text'  => 'required|string',
        'correct_option' => 'required|string',
        'option_a'       => 'nullable|string',
        'option_b'       => 'nullable|string',
        'option_c'       => 'nullable|string',
        'option_d'       => 'nullable|string',
    ]);

    // Retrieve the quiz question
    $quizQuestion = QuizQuestion::findOrFail($id);
    
    // Update the quiz question with the validated data
    $quizQuestion->update($validated);

    return redirect()->route('quizquestions.view')->with('success', 'Quiz question updated successfully.');
}

public function destroy($id)
{
    $quizQuestion = QuizQuestion::findOrFail($id);
    $quizQuestion->delete();
    return redirect()->route('quizquestions.view')->with('success', 'Quiz question deleted successfully.');
}

public function search(Request $request)
{
    $query = $request->input('search');
    $quizId = $request->input('quiz_id');

    // Retrieve questions, filtering by quiz if selected, and by question text if provided
    $quizQuestions = QuizQuestion::with('quiz')
        ->whereHas('quiz', function ($q) {
            // If you only want to show quizzes that belong to the current instructor:
            $q->whereHas('chapter.course', function ($q2) {
                $q2->where('user_id', Auth::id());
            });
        })
        ->when($quizId, function ($q) use ($quizId) {
            $q->where('quiz_id', $quizId);
        })
        ->when($query, function ($q) use ($query) {
            // Assuming the column for question text is "question_text"
            $q->where('question_text', 'LIKE', "%{$query}%");
        })
        ->paginate(10);

    // Retrieve quizzes for the dropdown
    $quizzes = Quiz::with('chapter.course')
        ->whereHas('chapter.course', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->get();

    // Return to your quiz question index view (or whatever Blade file you have)
    return view('instructor.quizquestions.view_quizquestions', compact('quizQuestions', 'quizzes'));
}

    
   
}
