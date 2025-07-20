<?php

namespace App\Http\Controllers\Instructor;

namespace App\Http\Controllers;

use Flasher\SweetAlert\Prime\SweetAlertInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\User;
use App\Models\Content;
use App\Models\Lesson;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizController extends Controller
{

    // عرض الاختبارات الخاصة بالمستخدم الحالي
    public function index()
{
    $quizzes = Quiz::with('chapter.course')
        ->whereHas('chapter.course', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->paginate(10);

    // Retrieve courses for the dropdown (only those owned by the logged-in instructor)
    $courses = Course::where('user_id', Auth::id())->get();

    return view('instructor.quizzes.View_Quiz', compact('quizzes', 'courses'));
}


    // عرض نموذج الإضافة مع قائمة من الدورات الخاصة بالمستخدم فقط
    public function createWithDropdown()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        return view('instructor.quizzes.add_quiz', compact('courses'));
    }

    // تخزين الاختبار بعد التأكد من صحة البيانات وملكيتها للمستخدم الحالي
    public function store(Request $request)
    {
        $request->validate([
            'course_id'       => 'required|integer',
            'chapter_id'      => 'required|integer',
            'title'           => 'required|string|max:255',
            'time_limit'      => 'nullable|integer',
            'passing_score'   => 'nullable|integer',
        ]);

        // التأكد أن الفصل (Chapter) ينتمي لدورة يملكها المستخدم الحالي
        $chapter = Chapter::with('course')->findOrFail($request->chapter_id);
        if ($chapter->course->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'عملية غير مصرح بها.');
        }

        $quiz = Quiz::create([
            'chapter_id'    => $request->chapter_id,
            'title'         => $request->title,
            'time_limit'    => $request->time_limit,
            'passing_score' => $request->passing_score,
        ]);

        // Get the course and its enrolled students
        $course = $chapter->course;
        
        // Send notification to all enrolled students
        foreach ($course->users as $student) {
            $student->notify(new \App\Notifications\NewCourseContentNotification(
                $quiz,
                'quiz',
                $course
            ));
        }

        return redirect()->route('instructor.quizzes.index')->with('success', 'Quiz Add Successfully');
    }

    // عرض تفاصيل الاختبار بعد التأكد من ملكيته للمستخدم
    public function show($id)
    {
        $quiz = Quiz::with('chapter.course')
            ->whereHas('chapter.course', function ($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($id);

        return view('instructor.quizzes.show', compact('quiz'));
    }

    // عرض نموذج تعديل الاختبار مع التأكد من ملكيته للمستخدم
    public function edit($id)
    {
        $quiz = Quiz::with('chapter.course')
            ->whereHas('chapter.course', function ($query) {
                $query->where('user_id', Auth::id());
            })->find($id);

        if (!$quiz) {
            return redirect()->back()->with('error', 'Unauthorized or quiz not found!');
        }

        $courses = Course::where('user_id', Auth::id())->get();
        $chapters = Chapter::where('course_id', $quiz->chapter->course->id)->get();

        return view('instructor.quizzes.update_quiz', compact('quiz', 'courses', 'chapters'));
    }

    // تحديث الاختبار بعد التحقق من صحة البيانات وملكيتها للمستخدم الحالي
    public function update(Request $request, $id)
    {
        $quiz = Quiz::with('chapter.course')
        ->whereHas('chapter.course', function ($query) {
            $query->where('user_id', Auth::id());
        })->find($id);

if (!$quiz) {
return redirect()->back()->with('error', 'Unauthorized or Quiz not found!');
}
        $request->validate([
            'course_id'       => 'required|integer',
            'chapter_id'      => 'required|integer',
            'title'           => 'required|string|max:255',
            'time_limit'      => 'nullable|integer',
            'passing_score'   => 'nullable|integer',
        ]);

        // التأكد من أن الفصل الجديد ينتمي أيضاً لدورة يملكها المستخدم الحالي
        $chapter = Chapter::with('course')->findOrFail($request->chapter_id);
        if ($chapter->course->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'عملية غير مصرح بها.');
        }

        $quiz->update([
            'chapter_id'    => $request->chapter_id,
            'title'         => $request->title,
            'time_limit'    => $request->time_limit,
            'passing_score' => $request->passing_score,
        ]);

        return redirect()->route('instructor.quizzes.index')
            ->with('success', 'Quiz updated successfully');
    }

    // حذف الاختبار بعد التأكد من ملكيته للمستخدم الحالي
    public function destroy($id)
    {
        $quiz = Quiz::with('chapter.course')
            ->whereHas('chapter.course', function ($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($id);

        $quiz->delete();

        return redirect()->route('instructor.quizzes.index')
            ->with('success', 'Quiz deleted successfully');
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $courseId = $request->input('course_id');
    
        $quizzes = Quiz::with('chapter.course')
            ->whereHas('chapter.course', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%");
            })
            ->when($courseId, function ($q) use ($courseId) {
                $q->whereHas('chapter.course', function ($q2) use ($courseId) {
                    $q2->where('id', $courseId);
                });
            })
            ->paginate(10);
    
        // Retrieve courses for the dropdown
        $courses = Course::where('user_id', Auth::id())->get();
    
        return view('instructor.quizzes.View_Quiz', compact('quizzes', 'courses'));
    }
    
    

    
    

}
