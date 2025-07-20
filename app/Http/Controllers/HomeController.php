<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstructorAccessRequestMail;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InstructorAccessRequestNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\InstructorRequest;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Task;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\UserCourse;

class HomeController extends Controller
{
    
    public function home()
    {
        return view('Home.index');
    }

   
    public function courses_page()
    {
        $course = Course::where('status', 'approved')
            ->with('category')
            ->paginate(6); 
        $categories = Category::all();
        return view('home.courses_page', compact('course', 'categories'));
    }

   
    public function coursesByCategory($id)
    {
       
        $categories = Category::all();
    
       
        $course = Course::where('category_id', $id)
                ->where('status', 'approved')
                ->paginate(6); 
    
     
        return view('home.courses_page', compact('course', 'categories'));
    }

    public function searchCourses(Request $request)
    {
        $search = $request->input('search');

        $course = Course::where('status', 'approved')
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('long_title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->with('category')
            ->paginate(6);

        $categories = Category::all();

        return view('home.courses_page', compact('course', 'categories'));
    }
    
    public function incourse($id)
    {
        $course = Course::with('insightQuests')->findOrFail($id);
        return view('home.incourse', compact('course'));
    }
    public function courseContent($id, Request $request)
    {
           $course = Course::with(['chapters.lessons', 'chapters.quizzes'])->findOrFail($id);

        // Retrieve the course with its chapters and lessons
        $course = Course::with('chapters.lessons')->findOrFail($id);

        // Get the lesson ID from query string if provided
        $lessonId = $request->query('lesson');

        // Determine the current video lesson
        if ($lessonId) {
            $videoLesson = Lesson::find($lessonId);
        } else {
            $videoLesson = $course->chapters->isNotEmpty() && $course->chapters->first()->lessons->isNotEmpty()
                ? $course->chapters->first()->lessons->first()
                : null;
        }

        // Process YouTube URL conversion if videoLesson has a YouTube video_url
        if ($videoLesson && isset($videoLesson->video_url) &&
            (str_contains($videoLesson->video_url, 'youtube.com') || str_contains($videoLesson->video_url, 'youtu.be'))) {
            $videoLesson->converted_video_url = $this->convertYoutubeUrl($videoLesson->video_url);
        }

        // Gather all lessons from all chapters, ordered by 'order'
        $allLessons = collect();
        foreach ($course->chapters as $chapter) {
            $sortedLessons = $chapter->lessons->sortBy('order');
            $allLessons = $allLessons->merge($sortedLessons);
        }

        // Determine the next and previous lessons
        $nextLesson = null;
        $prevLesson = null;
        if ($videoLesson) {
            $index = $allLessons->search(function ($lesson) use ($videoLesson) {
                return $lesson->id == $videoLesson->id;
            });

            if ($index !== false) {
                if ($index < $allLessons->count() - 1) {
                    $nextLesson = $allLessons->get($index + 1);
                }
                if ($index > 0) {
                    $prevLesson = $allLessons->get($index - 1);
                }
            }
        }

        // Pass the course, current video lesson, next and previous lessons to the view
        $user = Auth::user();
        return view('home.course_content', compact('course', 'videoLesson', 'nextLesson', 'prevLesson', 'user'));
        
    }

    // Display the courses that the authenticated user is enrolled in
    public function showEnrolledCourses()
    {
        $userCourses = \App\Models\UserCourse::with('course')
                           ->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)
                           ->get();
        return view('your_view_name', compact('userCourses'));
    }

    public function requestInstructorAccess(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $existingRequest = InstructorRequest::where('user_id', $user->id)
                            ->where('status', 'pending')
                            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You have already sent an instructor access request.');
        }

        // Send email notification to admin(s)
        $admins = User::where('usertype', 'admin')->get();
        Notification::send($admins, new InstructorAccessRequestNotification($user));

        // Create instructor request record
        InstructorRequest::create([
            'user_id' => $user->id,
            'status'  => 'pending',
            'message' => 'Instructor access request',
        ]);

        return redirect()->back()->with('success', 'Instructor access request has been sent successfully.');
    }

    /**
     * Get the authenticated user's enrolled courses
     */
    public function getUserCourses()
    {
        $courses = auth()->user()->courses()->with('category')->get();
        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }

    /**
     * Get the next tasks for a specific course
     */
    public function getNextTask($courseId)
    {
        $course = Course::findOrFail($courseId);
        $tasks = collect();
        
        foreach ($course->chapters as $chapter) {
            $chapterTasks = $chapter->tasks()->with(['taskPoints', 'submissions' => function($query) {
                $query->where('user_id', auth()->id());
            }])->get();
            
            $tasks = $tasks->merge($chapterTasks);
        }

        $tasks = $tasks->map(function($task) {
            $submission = $task->submissions->first();
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date,
                'status' => $submission ? $submission->status : 'not_submitted',
                'grade' => $submission ? $submission->grade : null,
                'taskPoints' => $task->taskPoints
            ];
        });

        return response()->json([
            'success' => true,
            'tasks' => $tasks
        ]);
    }

    // Private function to convert YouTube URLs to embed URLs
    private function convertYoutubeUrl($url)
    {
        $url = trim($url);
        if (str_contains($url, 'youtube.com/embed/')) {
            return $url;
        }
        if (preg_match('#https?://youtu\.be/([^?\s]+)#', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        if (preg_match('#https?://(www\.)?youtube\.com/watch\?v=([^&\s]+)#', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[2];
        }
        if (preg_match('#https?://(www\.)?youtube\.com/shorts/([^?\s]+)#', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[2];
        }
        return $url;
    }

    public function searchLessons(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]); // Return empty array if no query
        }

        // Get the authenticated user's purchased courses
        $user = \Illuminate\Support\Facades\Auth::user();
        $purchasedCourseIds = $user->courses->pluck('id');

        // Search lessons only within the user's purchased courses
        $lessons = Lesson::with('chapter.course')
            ->whereHas('chapter.course', function ($q) use ($purchasedCourseIds) {
                $q->whereIn('id', $purchasedCourseIds);
            })
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('long_description', 'LIKE', "%{$query}%");
            })
            ->limit(10) // Limit the number of results
            ->get()
            ->unique('id'); // Ensure no duplicate lessons

        // Format the results to include the URL and description
        $results = $lessons->map(function ($lesson) {
            // Ensure chapter and course relationships exist to avoid errors
            if ($lesson->chapter && $lesson->chapter->course) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'description' => $lesson->description, // Include description in the results
                    'url' => route('course.content', [
                        'id' => $lesson->chapter->course->id,
                        'lesson' => $lesson->id
                    ]),
                    'course_title' => $lesson->chapter->course->title // Optional: Add course title for context
                ];
            }
            return null; // Exclude lessons if relationships are missing
        })->filter(); // Remove null entries

        return response()->json($results);
    }
    public function getCategories()
{
    $categories = Category::all();
    return response()->json($categories);
}
public function loadMoreCourses(Request $request)
{
    $page = $request->input('page', 1);
    $categoryId = $request->input('category');

    $query = Course::where('status', 'approved')
        ->with('category');

    if ($categoryId && $categoryId != 'all') {
        $query->where('category_id', $categoryId);
    }

    $courses = $query->paginate(6, ['*'], 'page', $page);

    if ($request->ajax()) {
        $view = view('home.partials.course_cards', compact('courses'))->render();
        return response()->json(['html' => $view, 'hasMorePages' => $courses->hasMorePages()]);
    }

    return response()->json(['error' => 'Invalid request'], 400);
}

// public function showQuiz(Quiz $quiz)
// {
//     // استعلام عن محاولة سابقة للمستخدم الحالي لهذا الامتحان
//     $previousAttempt = QuizResult::where('quiz_id', $quiz->id)
//                         ->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)
//                         ->first();

//     // نحول النتيجة إلى متغير بوليني
//     $hasAttempted = !is_null($previousAttempt);

//     return view('Home.quiz.show', compact('quiz', 'hasAttempted'));
// }

public function showQuiz(Quiz $quiz)
{
    // Eager load relationships needed in the view
    $quiz->load('questions', 'chapter.course.user');

    // Find the latest previous attempt for the currently authenticated user for this specific quiz
    $previousAttempt = QuizResult::where('quiz_id', $quiz->id)
                        ->where('user_id', Auth::id()) // Use Auth::id() for the logged-in user
                        ->latest('created_at') // Get the most recent attempt
                        ->first();

    // Determine if the user has attempted this quiz based on whether a result was found
    $hasAttempted = !is_null($previousAttempt);

    // Extract course if available
    $course = $quiz->chapter && $quiz->chapter->course ? $quiz->chapter->course : null;

    // Pass the quiz model, the attempt status (boolean), the previous result object (or null), and the course to the view
    return view('Home.quiz.show', compact('quiz', 'hasAttempted', 'previousAttempt', 'course'));
}

public function show($id)
{
    // Eager-load quiz questions using the relationship
    $quiz = Quiz::with('questions')->findOrFail($id);

    return view('Home.quiz.show', compact('quiz'));
}

public function markLessonWatched($lessonId)
{
    $user = Auth::user();
    $lesson = Lesson::find($lessonId);
    if ($user && $lesson) {
        // Use syncWithoutDetaching so that existing relationships aren't overwritten.
        $user->lessons()->syncWithoutDetaching([$lessonId => ['watched' => true]]);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 404);
}

public function showTask(Task $task)
{
    $user = Auth::user();
    if (! $user || ! $user->courses()->where('course_id', $task->course_id)->exists()) {
        abort(403, 'You are not authorized to view this task.');
    }

    // Get all tasks in the same chapter ordered by creation date
    $tasks = Task::where('chapter_id', $task->chapter_id)
                ->orderBy('created_at', 'asc')
                ->get();

    // Find the current task's position
    $currentIndex = $tasks->search(function($item) use ($task) {
        return $item->id === $task->id;
    });

    // Get previous and next tasks
    $previousTask = $currentIndex > 0 ? $tasks[$currentIndex - 1] : null;
    $nextTask = $currentIndex < $tasks->count() - 1 ? $tasks[$currentIndex + 1] : null;

    // Load the course data
    $course = Course::findOrFail($task->course_id);
    $task->load('taskPoints');
    
    return view('home.task.show_task', compact('task', 'previousTask', 'nextTask', 'course'));
}

public function submitTask(Request $request, Task $task)
{
    // Validate the request
    $request->validate([
        'files.*' => 'required|file|mimes:jpeg,png,gif,mp4,pdf,psd,ai,doc,docx,ppt,pptx|max:10240',
        'notes' => 'nullable|string',
    ]);

    // Create task submission
    $submission = \App\Models\TaskSubmission::create([
        'user_id' => auth()->id(),
        'task_id' => $task->id,
        'notes' => $request->notes,
        'status' => 'pending'
    ]);

    // Handle file uploads
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            // Generate unique file name
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store file in storage/app/public/task_submissions
            $filePath = $file->storeAs('task_submissions', $fileName, 'public');
            
            // Create file record in database
            \App\Models\SubmissionFile::create([
                'task_submission_id' => $submission->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize()
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Task submitted successfully',
        'submission_id' => $submission->id
    ]);
}

public function test()
    {
        // --- Step 1: Fetch the Task Data ---

        // Option A: Fetch the *first* available task owned by the user (for simple testing)
        // Make sure the relationships are correctly defined in Task and TaskPoint models
        // $task = Task::whereHas('course', fn($q) => $q->where('user_id', Auth::id())) // Optional: Scope to user
        //             ->with('taskPoints') // Eager load the points
        //             ->orderBy('created_at', 'desc') // Get the latest task maybe?
        //             ->first();

        // Option B: Fetch a *specific* task by its ID (replace '1' with the desired ID)
        $taskIdToTest = 1; // <--- CHANGE THIS ID TO THE TASK YOU WANT TO TEST
        $task = Task::with('taskPoints') // Eager load the structured points
                    ->find($taskIdToTest); // Use find() for specific ID

        // Option C: More robustly find a specific task and handle not found
        // $taskIdToTest = 1;
        // $task = Task::with('taskPoints')->find($taskIdToTest);
        // if (!$task) {
        //     // Handle the case where the task doesn't exist
        //     abort(404, "Task with ID {$taskIdToTest} not found.");
        //     // Or redirect with an error message:
        //     // return redirect()->route('some.route')->with('error', "Task not found.");
        // }

        // Optional: Authorization check (if necessary for the test)
        // if ($task && $task->course->user_id !== Auth::id()) {
        //     abort(403, 'Unauthorized to view this task.');
        // }


        // --- Step 2: Pass Data to the View ---

        // Check if a task was actually found before passing it
        if (!$task) {
             // If you didn't use abort() above, handle the 'not found' case here
             // You could pass null or an empty object, or redirect.
             // For simplicity, let's just return the view with a message or potentially null task
             return view('Home.test', ['task' => null]) // Pass null explicitly
                    ->with('errorMessage', "Task with ID {$taskIdToTest} not found or could not be loaded."); // Optional error message
        }


        // Pass the fetched $task object to the view
        return view('Home.test', compact('task'));
        // Alternatively: return view('Home.test', ['task' => $task]);
    }

    public function getQuizStatistics()
    {
        $stats = DB::table('quiz_results')
            ->select(
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('AVG(percentage) as average_marks')
            )
            ->where('user_id', auth()->id())
            ->first();

        return response()->json([
            'total_attempts' => $stats->total_attempts,
            'average_marks' => round($stats->average_marks, 1)
        ]);
    }

    public function getCourseQuizStatistics($courseId)
    {
        $stats = DB::table('quiz_results')
            ->join('quizzes', 'quiz_results.quiz_id', '=', 'quizzes.id')
            ->join('chapters', 'quizzes.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $courseId)
            ->where('quiz_results.user_id', auth()->id())
            ->select(
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('AVG(quiz_results.percentage) as average_marks'),
                DB::raw('GROUP_CONCAT(quiz_results.percentage) as monthly_progress')
            )
            ->first();

        // Get monthly progress data for the line chart
        $monthlyProgress = DB::table('quiz_results')
            ->join('quizzes', 'quiz_results.quiz_id', '=', 'quizzes.id')
            ->join('chapters', 'quizzes.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $courseId)
            ->where('quiz_results.user_id', auth()->id())
            ->select(
                DB::raw('MONTH(quiz_results.created_at) as month'),
                DB::raw('AVG(quiz_results.percentage) as average_score')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyData = array_fill(1, 12, 0); // Initialize all months with 0
        foreach ($monthlyProgress as $progress) {
            $monthlyData[$progress->month] = round($progress->average_score, 1);
        }

        return response()->json([
            'total_attempts' => $stats->total_attempts,
            'average_marks' => round($stats->average_marks ?? 0, 1),
            'monthly_progress' => array_values($monthlyData)
        ]);
    }

    public function getCourseTaskStatistics($courseId)
    {
        $stats = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
            ->join('chapters', 'tasks.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $courseId)
            ->where('task_submissions.user_id', auth()->id())
            ->where('task_submissions.status', 'reviewed')
            ->select(
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('AVG(grade) as average_grade')
            )
            ->first();

        // Get monthly progress data for the line chart
        $monthlyProgress = DB::table('task_submissions')
            ->join('tasks', 'task_submissions.task_id', '=', 'tasks.id')
            ->join('chapters', 'tasks.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $courseId)
            ->where('task_submissions.user_id', auth()->id())
            ->where('task_submissions.status', 'reviewed')
            ->select(
                DB::raw('MONTH(task_submissions.created_at) as month'),
                DB::raw('AVG(grade) as average_grade')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyData = array_fill(1, 12, 0); // Initialize all months with 0
        foreach ($monthlyProgress as $progress) {
            $monthlyData[$progress->month] = round($progress->average_grade, 1);
        }

        return response()->json([
            'total_attempts' => $stats->total_attempts ?? 0,
            'average_grade' => round($stats->average_grade ?? 0, 1),
            'monthly_progress' => array_values($monthlyData)
        ]);
    }

    public function getCourseLessonStatistics($courseId)
    {
        $user = auth()->user();
        
        // Get total lessons in the course
        $totalLessons = Lesson::whereHas('chapter', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->count();

        // Get watched lessons count
        $watchedLessons = DB::table('lesson_user')
            ->join('lessons', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->join('chapters', 'chapters.id', '=', 'lessons.chapter_id')
            ->where('lesson_user.user_id', $user->id)
            ->where('chapters.course_id', $courseId)
            ->count();

        // Calculate monthly progress
        $monthlyProgress = DB::table('lesson_user')
            ->join('lessons', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->join('chapters', 'chapters.id', '=', 'lessons.chapter_id')
            ->where('lesson_user.user_id', $user->id)
            ->where('chapters.course_id', $courseId)
            ->select(DB::raw('MONTH(lesson_user.created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('lesson_user.created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0
        $monthlyData = array_fill(1, 12, 0);
        foreach ($monthlyProgress as $month => $count) {
            $monthlyData[$month] = $count;
        }

        // Calculate completion percentage
        $completionPercentage = $totalLessons > 0 ? round(($watchedLessons / $totalLessons) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'total_lessons' => $totalLessons,
            'watched_lessons' => $watchedLessons,
            'completion_percentage' => $completionPercentage,
            'monthly_progress' => array_values($monthlyData)
        ]);
    }

    /**
     * Dashboard with top purchased courses
     */
    public function dashboard()
    {
        // Get top 4 most purchased courses that are approved
        $topCourses = Course::where('status', 'approved')
            ->withCount('users')
            ->orderBy('users_count', 'desc')
            ->take(4)
            ->get();
        return view('Home.dashboard', compact('topCourses'));
    }

    /**
     * Mark a notification as read
     */
    public function markNotificationAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notification marked as read');
    }

    public function generateCertificate($courseId)
    {
        $user = auth()->user();
        $course = Course::findOrFail($courseId);
        
        // Verify user has completed the course
        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();
            
        if (!$userCourse) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // Generate certificate number
        $certificateNumber = 'CERT-' . strtoupper(substr(md5($user->id . $courseId . time()), 0, 8));
        
        // Generate PDF
        $pdf = PDF::loadView('certificates.course-completion', [
            'user' => $user,
            'course' => $course,
            'certificateNumber' => $certificateNumber,
            'completionDate' => now()->format('F d, Y')
        ]);

        // Set PDF options
        $pdf->setPaper('a4', 'landscape');
        
        // Generate filename
        $filename = 'Certificate_' . str_replace(' ', '_', $course->title) . '_' . $user->first_name . '_' . $user->last_name . '.pdf';
        
        // Return PDF download
        return $pdf->download($filename);
    }

    public function liveSearchCourses(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $lessons = Lesson::with('chapter.course')
            ->whereHas('chapter.course', function ($q) {
                $q->where('status', 'approved');
            })
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get();

        $results = $lessons->map(function ($lesson) {
            if ($lesson->chapter && $lesson->chapter->course) {
                return [
                    'title' => $lesson->title,
                    'course_title' => $lesson->chapter->course->title,
                    'url' => route('course.content', [
                        'id' => $lesson->chapter->course->id,
                        'lesson' => $lesson->id
                    ]),
                ];
            }
            return null;
        })->filter();

        return response()->json($results);
    }

}
