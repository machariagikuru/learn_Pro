<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Flasher\SweetAlert\Prime\SweetAlertInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use App\Models\Content;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Notifications\CourseUploadedNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\DB;



class InstructorController extends Controller
{
      /**
     * Display the instructor's dashboard view.
     */
    public function index()
    {
        return view('instructor.index');
    }
    // -------------------------------------------------------------
    // Category Management
    // -------------------------------------------------------------
    public function view_category()
    {
        $data = Category::all();
        return view('instructor/category.category', compact('data'));
    }

    public function create()
    {
        return view('instructor.category.add_category');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);

        Category::create([
            'category_name' => $request->category,
        ]);

        return redirect(url('view_category'))->with('success', 'Category added successfully');
    }
    
    public function delete_category($id)
    {
        $category = Category::find($id);
        if (Course::where('category_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete category because it has associated courses. Please delete the courses first.');
        }
        $category->delete();
        return redirect()->back()->with('success', 'Category Deleted Successfully');
    }

    public function edit_category($id)
    {
        $data = Category::find($id);
        return view('instructor/category.edit_category', compact('data'));
    }

    public function update_category(Request $request, $id)
    {
        $data = Category::find($id);
        $data->category_name = $request->category;
        $data->save();
        return redirect('/view_category')->with('success', 'Category Updated Successfully.');
    }

    // -------------------------------------------------------------
    // Course Management
    // -------------------------------------------------------------

    public function add_course()
    {
        $category = Category::all();
        return view('instructor/course.add_course', compact('category'));
    }



public function upload_course(Request $request)
{
    $messages = [
        'image.required'         => 'The course image is required.',
        'image.image'            => 'The uploaded file must be an image.',
        'image.mimes'            => 'The image must be of type: jpeg, png, jpg, gif.',
        'image.max'              => 'The image size must not exceed 2MB.',
        'short_video.required_without' => 'The short video is required if no video URL is provided.',
        'short_video.mimes'      => 'The video must be of type: mp4, mov, avi.',
        'short_video.max'        => 'The video size must not exceed 10MB.',
        'title.required'         => 'The course title is required.',
        'title.max'              => 'The title must not exceed 255 characters.',
        'long_title.string'      => 'The long title must be a valid string.',
        'long_title.max'         => 'The long title must not exceed 255 characters.',
        'description.required'   => 'The course description is required.',
        'duration.required'      => 'The course duration is required.',
        'duration.integer'       => 'The duration must be an integer.',
        'duration.min'           => 'The duration must be at least 1 minute.',
        'price.required'         => 'The course price is required.',
        'price.numeric'          => 'The price must be a number.',
        'price.min'              => 'The price must be at least 0.',
        'price.max'              => 'The price must not exceed 999,999.99.',
        'category.required'      => 'The course category is required.',
        'rate.required'          => 'The course rating is required.',
        'rate.min'               => 'The rating must be at least 1.',
        'rate.max'               => 'The rating must not exceed 5.',
        'video_url.required_without' => 'The video URL is required if no short video is provided.',
        'video_url.url'          => 'Please provide a valid URL for the video.',
    ];

    $request->validate([
        'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'short_video' => 'required_without:video_url|mimes:mp4,mov,avi|max:51200',
        'title'       => 'required|string|max:255',
        'long_title'  => 'sometimes|string|max:255',
        'description' => 'required|string',
        'duration'    => 'required|numeric|min:0.1',
        'price'       => 'required|numeric|min:0|max:999999.99',
        'category'    => 'required|string|max:255',
        'rate'        => 'sometimes|numeric|min:1|max:5',
        'video_url'   => 'required_without:short_video|nullable|url',
        'why_choose_this_course' => 'required|string',
    ], $messages);

    $data = new Course;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagename = time() . '_' . $image->getClientOriginalName();
        $image->move('courses', $imagename);
        $data->image = $imagename;
    }

    if ($request->hasFile('short_video')) {
        $shortVideo = $request->file('short_video');
        $videoName = time() . '_' . $shortVideo->getClientOriginalName();
        $shortVideo->move('courses', $videoName);
        $data->short_video = $videoName;
        $data->video_url = null;
    } elseif ($request->filled('video_url')) {
        $data->video_url = $request->video_url;
        $data->short_video = null;
    }

    $data->title       = $request->title;
    $data->long_title  = $request->long_title;
    $data->description = $request->description;
    $data->duration    = $request->duration;
    $data->price       = $request->price;
    $data->category_id = $request->category;
    $data->user_id     = Auth::id();
    $data->rate        = $request->rate;
    $data->why_choose_this_course = $request->why_choose_this_course;
    // Set status to pending until admin approves it
    $data->status = 'pending';

    $data->save();

    // Load the user relationship before sending notification
    $data->load('user');

    // إرسال إشعار للأدمن عند رفع الكورس
    // نفترض أن الأدمن يتم تحديدهم باستخدام حقل role وتكون قيمته 'admin'
    $admins = User::where('usertype', 'admin')->get();
    Notification::send($admins, new CourseUploadedNotification($data));

    return redirect(url('view_course'))->with('success', 'Your course has been submitted and will be uploaded after admin approval.');
}



    public function view_course()
    {
        $courses = Course::with('category')
            ->where('user_id', Auth::id())
            ->paginate(5);
        return view('instructor/course.view_course', compact('courses'));
    }

    public function delete_course($id)
    {
        $course = Course::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$course) {
            return redirect()->back()->with('error', 'Unauthorized or Course not found.');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }
        if ($course->image && file_exists(public_path('courses/' . $course->image))) {
            unlink(public_path('courses/' . $course->image));
        }
        if ($course->short_video && file_exists(public_path('courses/' . $course->short_video))) {
            unlink(public_path('courses/' . $course->short_video));
        }
        $course->delete();
        return redirect()->back()->with('success', 'Course deleted successfully.');
    }

    public function update_course($id)
    {
        $data = Course::find($id);
        $categories = Category::all();
        $course = Course::where('id', $id)->where('user_id', Auth::id())->first();
    if (!$course) {
        return redirect()->back()->with('error', 'Unauthorized or Course not found!');
    }
        return view('instructor/course.update_page', compact('data', 'categories'));
    }

    public function updateCourse(Request $request, $id)
    {
        $messages = [
            'image.image'         => 'The uploaded file must be an image.',
            'image.mimes'         => 'The image must be of type: jpeg, png, jpg, gif.',
            'image.max'           => 'The image size must not exceed 2MB.',
            'short_video.mimes'   => 'The video must be of type: mp4, mov, avi.',
            'short_video.max'     => 'The video size must not exceed 10MB.',
            'title.required'      => 'The course title is required.',
            'title.max'           => 'The title must not exceed 255 characters.',
            'long_title.string'   => 'The long title must be a valid string.',
            'long_title.max'      => 'The long title must not exceed 255 characters.',
            'description.required'=> 'The course description is required.',
            'duration.required'   => 'The course duration is required.',
            'duration.integer'    => 'The duration must be an integer (in hours).',
            'duration.min'        => 'The duration must be at least 1 hour.',
            'price.required'      => 'The course price is required.',
            'price.numeric'       => 'The price must be a number.',
            'price.min'           => 'The price must be at least 0.',
            'price.max'           => 'The price must not exceed 999,999.99.',
            'category.required'   => 'The course category is required.',
            'rate.min'            => 'The rating must be at least 1.',
            'rate.max'            => 'The rating must not exceed 5.',
        ];

        $request->validate([
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_video' => 'sometimes|mimes:mp4,mov,avi|max:51200',
            'title'       => 'required|string|max:255',
            'long_title'  => 'sometimes|string|max:255',
            'description' => 'required|string',
            'why_choose_this_course' => 'required|string',
            'duration'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0|max:999999.99',
            'category'    => 'required|integer|exists:categories,id',
            'rate'        => 'required|numeric|min:1|max:5',
            'video_url'   => 'sometimes|nullable|url',
        ], $messages);

        $course = Course::find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found!');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('courses'), $imagename);
            $course->image = $imagename;
        }

        if ($request->hasFile('short_video')) {
            $shortVideo = $request->file('short_video');
            $videoName = time() . '_' . $shortVideo->getClientOriginalName();
            $shortVideo->move(public_path('courses'), $videoName);
            $course->short_video = $videoName;
            $course->video_url = null;
        } elseif ($request->filled('video_url')) {
            $course->video_url = $request->video_url;
            $course->short_video = null;
        }

        $course->title       = $request->title;
        $course->long_title  = $request->long_title;
        $course->description = $request->description;
        $course->why_choose_this_course = $request->why_choose_this_course;
        $course->duration    = $request->duration;
        $course->price       = $request->price;
        $course->category_id = $request->category;
        $course->rate        = $request->rate;
        $course->save();

        return redirect(url('view_course'))->with('success', 'Course updated successfully');
    }

    public function searchCourse(Request $request)
    {
        $query = $request->input('search');
        $courses = Course::where('user_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('long_title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->paginate(10);
    
        return view('instructor/course.view_course', compact('courses'));
    }

    public function searchCategory(Request $request)
    {
    $query = $request->input('search');
    $data = Category::where('category_name', 'LIKE', "%{$query}%")
                    ->paginate(10);
    return view('instructor/category.category', compact('data'));
    }

    public function searchChapter(Request $request)
{
    $query = $request->input('search');
    $chapters = Chapter::with('course')
        ->whereHas('course', function($q) {
            $q->where('user_id', Auth::id());
        })
        ->where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%");
        })
        ->paginate(10);
    $courses = Course::where('user_id', Auth::id())->get();
    return view('instructor/chapter.view_chapters', compact('chapters', 'courses'));
}

    public function searchLesson(Request $request)
    {
        $query = $request->input('search');
        $lessons = Lesson::with('chapter')
                    ->whereHas('chapter.course', function ($q) {
                        $q->where('user_id', Auth::id());
                    })
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    })
                    ->paginate(10);
        $courses = Course::where('user_id', Auth::id())->get();
        return view('instructor.lesson.view_lessons', compact('lessons', 'courses'));
    }



    
    // -------------------------------------------------------------
    // Chapter Management
    // -------------------------------------------------------------

    public function showAddChapterForm()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        return view('instructor/chapter.add_chapter', compact('courses'));
    }

    public function storeChapter(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer',
        ]);

        Chapter::create([
            'course_id'   => $request->course_id,
            'title'       => $request->title,
            'description' => $request->description,
            'order'       => $request->order ?? 0,
        ]);

        return redirect()->route('view.chapters')->with('success', 'Chapter added successfully');

    }
    
    // -------------------------------------------------------------
    // Lesson Management
    // -------------------------------------------------------------

    public function showAddLessonForm()
    {
     
        $courses = Course::where('user_id', Auth::id())->get();
        return view('instructor/lesson.add_lesson', compact('courses'));
    }

    public function getChaptersByCourse($courseId)
    {
        $chapters = Chapter::where('course_id', $courseId)->get();
        return response()->json($chapters);
    }

    public function storeLesson(Request $request)
    {
        $request->validate([
            'chapter_id'      => 'required|exists:chapters,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'long_description'=> 'nullable|string',
            'video_url'       => 'nullable|string',
            'video_file'      => 'nullable|file|mimes:mp4,mov,avi|max:2048000',
            'order'           => 'nullable|integer',
        ]);

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoFile = $request->file('video_file');
            $videoName = time() . '_' . $videoFile->getClientOriginalName();
            $videoFile->move(public_path('courses/'), $videoName);
            $videoPath = 'courses/' . $videoName;
        }

        $lesson = Lesson::create([
            'chapter_id'      => $request->chapter_id,
            'title'           => $request->title,
            'description'     => $request->description,
            'long_description'=> $request->long_description,
            'video_url'       => $videoPath ?? $request->video_url,
            'order'           => $request->order ?? 0,
        ]);

        // Get the course and its enrolled students
        $course = $lesson->chapter->course;
        
        // Send notification to all enrolled students
        foreach ($course->users as $student) {
            $student->notify(new \App\Notifications\NewCourseContentNotification(
                $lesson,
                'lesson',
                $course
            ));
        }

        return redirect()->route('view.lessons')->with('success', 'Lesson added successfully');
    }
    
    public function viewLessons(Request $request)
    {
        $courses = Course::all();
        $lessonsQuery = Lesson::with('chapter')
            ->whereHas('chapter.course', function($query) {
                $query->where('user_id', Auth::id());
            });
    
        if ($request->filled('course_id')) {
            $courseId = $request->input('course_id');
            $lessonsQuery->whereHas('chapter.course', function($query) use ($courseId) {
                $query->where('id', $courseId);
            });
        }

        $lessons = $lessonsQuery->paginate(10);
        $lessons->appends($request->all());
        return view('instructor/lesson.view_lessons', compact('lessons','courses'));
    }
    
   
    public function viewChapters(Request $request)
    {
        $courses = Course::where('user_id', Auth::id())->get();
        $chaptersQuery = Chapter::with('course')
            ->whereHas('course', function($query) {
                $query->where('user_id', Auth::id());
            });

        if ($request->filled('course_id')) {
            $courseId = $request->input('course_id');
            $chaptersQuery->whereHas('course', function($query) use ($courseId) {
                $query->where('id', $courseId);
            });
        }

        $chapters = $chaptersQuery->paginate(10);
    
        return view('instructor/chapter.view_chapters', compact('chapters', 'courses'));
    }

    public function delete_chapter($id)
    {
        $chapter = Chapter::whereHas('course', function($query) {
            $query->where('user_id', Auth::id());
        })->find($id);
    
        if (!$chapter) {
            return redirect()->back()->with('error', 'Chapter not found or unauthorized.');
        }
        $chapter->delete();
        return redirect()->back()->with('success', 'Chapter deleted successfully.');
    }

  
    public function delete_lesson($id)
    {
      
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return redirect()->back()->with('error', 'Lesson not found.');
        }
        $lesson->delete();
        return redirect()->back()->with('success', 'Lesson deleted successfully.');
    }

    
    public function update_chapter($id)
    {
        $chapter = Chapter::whereHas('course', function($query) {
            $query->where('user_id', Auth::id());
        })->find($id);
    
        if (!$chapter) {
            return redirect()->back()->with('error', 'Chapter not found or unauthorized.');
        }
        return view('instructor/chapter.update_chapter', compact('chapter'));
    }

    public function updateChapter(Request $request, $id)
    {
        $messages = [
            'title.required'       => 'The chapter title is required.',
            'title.max'            => 'The chapter title must not exceed 255 characters.',
            'description.required' => 'The chapter description is required.',
            'order.required'       => 'The order is required.',
            'order.integer'        => 'The order must be an integer.',
            'order.min'            => 'The order must be at least 0.',
        ];

        $request->validate([
            'title'       => 'required|string|max:255',
        ], $messages);

        $chapter = Chapter::find($id);
      
        if (!$chapter) {
            return redirect()->back()->with('error', 'Chapter not found!');
        }

        $chapter->title       = $request->title;
        $chapter->description = $request->description;
        $chapter->order       = $request->order;
        $chapter->save();
        return redirect()->route('view.chapters')->with('success', 'Chapter updated successfully');

    }

    public function update_lesson($id)
    {
        $lesson = Lesson::whereHas('chapter.course', function($query) {
            $query->where('user_id', Auth::id());
        })->find($id);

        if (!$lesson) {
            return redirect()->back()->with('error', 'Lesson not found or unauthorized.');
        }

        $chapters = Chapter::whereHas('course', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('instructor/lesson.update_lesson', compact('lesson', 'chapters'));
    }

    public function updateLesson(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return redirect()->back()->with('error', 'Lesson not found!');
        }

        $rules = [
            'chapter_id'  => 'required|exists:chapters,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'order'       => 'nullable|integer|min:0',
            'long_description' => 'nullable|string',
        ];

        // Only require video or video_url if neither exists
        if (!$lesson->video && !$lesson->video_url) {
            $rules['video'] = 'required_without:video_url|nullable|mimes:mp4,mov,avi|max:51200';
            $rules['video_url'] = 'required_without:video|nullable|url';
        } else {
            $rules['video'] = 'nullable|mimes:mp4,mov,avi|max:51200';
            $rules['video_url'] = 'nullable|url';
        }

        $messages = [
            'chapter_id.required' => 'Please select a chapter.',
            'chapter_id.exists'   => 'Selected chapter does not exist.',
            'title.required'      => 'The lesson title is required.',
            'title.max'           => 'The title must not exceed 255 characters.',
            'description.required'=> 'The lesson description is required.',
            'video_url.url'       => 'Please provide a valid URL for the video.',
            'order.integer'       => 'The order must be an integer.',
            'order.min'           => 'The order must be at least 0.',
            'video.required_without' => 'The video field is required when video url is not present.',
            'video_url.required_without' => 'The video url field is required when video is not present.',
        ];

        $request->validate($rules, $messages);

        // Update basic fields
        $lesson->chapter_id  = $request->chapter_id;
        $lesson->title       = $request->title;
        $lesson->description = $request->description;
        $lesson->order       = $request->order;
        $lesson->long_description = $request->long_description;

        // If a new video file is uploaded, store it and set video_url to null
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoName = time() . '_' . $videoFile->getClientOriginalName();
            $videoFile->move(public_path('lessons'), $videoName);
            $lesson->video = 'lessons/' . $videoName;
            $lesson->video_url = null;
        }
        // Else, if no file is uploaded but a video_url is provided, store that and set video to null
        elseif ($request->filled('video_url')) {
            $lesson->video_url = $request->video_url;
            $lesson->video = null;
        }

        $lesson->save();

        return redirect()->route('view.lessons')->with('success', 'Lesson updated successfully');
    }

    

    public function getChapters(Request $request)
    {
        $courseId = $request->course_id;
        $chapters = Chapter::where('course_id', $courseId)->get();
        return response()->json($chapters);
    }
    
    public function markNotificationAsRead($id)
{
    $notification = Auth::user()->notifications()->find($id);
    if ($notification) {
        $notification->markAsRead();
    }
    return redirect()->back()->with('success', 'Notification marked as read.');
}


public function markNotificationAsUnread($id)
{
    $notification = Auth::user()->notifications()->find($id);
    if ($notification) {
        // إعادة ضبط حالة الإشعار إلى غير مقروءة
        $notification->update(['read_at' => null]);
    }
    return redirect()->back()->with('success', 'Notification marked as unread.');
}

public function notifications()
{
    return view('instructor.notifications');
}

public function markAllRead()
{
    Auth::user()->unreadNotifications->markAsRead();
    return redirect()->back()->with('success', 'All notifications marked as read.');
}

public function viewAllSubmissions(Request $request)
{
    $query = TaskSubmission::with(['user', 'task.chapter.course', 'files'])
        ->whereHas('task.chapter.course', function($q) {
            $q->where('user_id', Auth::id());
        });

    // Filter by course if selected
    if ($request->filled('course_id')) {
        $query->whereHas('task.chapter.course', function($q) use ($request) {
            $q->where('id', $request->course_id);
        });
    }

    // Filter by status if selected
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $submissions = $query->latest()->paginate(10);
    $courses = Course::where('user_id', Auth::id())->get();

    return view('instructor.tasks.submissions', compact('submissions', 'courses'));
}

public function reviewSubmission(Request $request, TaskSubmission $submission)
{
    // Validate instructor owns the course
    if ($submission->task->chapter->course->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    $request->validate([
        'grade' => 'required|integer|min:0|max:100',
        'feedback' => 'required|string'
    ]);

    $submission->update([
        'grade' => $request->grade,
        'feedback' => $request->feedback,
        'status' => 'reviewed'
    ]);

    // TODO: Send notification to student about the review

    return redirect()->back()->with('success', 'Submission reviewed successfully.');
}

public function courseEnrollmentAnalysis()
{
    // Get all courses for the authenticated instructor
    $instructorCourses = Course::where('user_id', Auth::id())->pluck('id');

    // Calculate total enrollments
    $totalEnrollments = DB::table('user_courses')
        ->whereIn('course_id', $instructorCourses)
        ->count();

    // Calculate active students
    $activeStudents = DB::table('user_courses')
        ->whereIn('course_id', $instructorCourses)
        ->where('status', 'active')
        ->count();

    // Calculate money flow (total earnings after platform fee)
    $moneyFlow = DB::table('user_courses')
        ->join('courses', 'user_courses.course_id', '=', 'courses.id')
        ->where('courses.user_id', Auth::id())
        ->sum(DB::raw('courses.price * 0.8')); // 20% platform fee

    // Get enrollment count and revenue for each course, ordered by enrollments
    $courseStats = Course::where('user_id', Auth::id())
        ->withCount('users as enrollments')
        ->get(['id', 'title', 'price'])
        ->sortByDesc('enrollments');

    // Get monthly enrollment data for the current year
    $monthlyEnrollments = DB::table('user_courses')
        ->join('courses', 'user_courses.course_id', '=', 'courses.id')
        ->where('courses.user_id', Auth::id())
        ->whereYear('user_courses.created_at', date('Y'))
        ->select(
            DB::raw('MONTH(user_courses.created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('month')
        ->get();

    // Format monthly data for chart
    $monthlyData = array_fill(1, 12, 0);
    foreach ($monthlyEnrollments as $data) {
        $monthlyData[$data->month] = $data->count;
    }

    // Get student details with their course interactions
    $studentEnrollments = DB::table('user_courses')
        ->join('courses', 'user_courses.course_id', '=', 'courses.id')
        ->join('users', 'user_courses.user_id', '=', 'users.id')
        ->leftJoin('lesson_user', function($join) {
            $join->on('users.id', '=', 'lesson_user.user_id')
                ->whereRaw('lesson_user.lesson_id IN (SELECT id FROM lessons WHERE chapter_id IN (SELECT id FROM chapters WHERE course_id = courses.id))');
        })
        ->where('courses.user_id', Auth::id())
        ->select(
            'users.id',
            'users.first_name',
            'users.last_name',
            'users.email',
            'courses.title as course_title',
            'user_courses.created_at as enrollment_date',
            'user_courses.status',
            DB::raw('COUNT(DISTINCT lesson_user.lesson_id) as lessons_watched')
        )
        ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email', 
                 'courses.title', 'user_courses.created_at', 'user_courses.status')
        ->orderBy('user_courses.created_at', 'desc')
        ->get();

    return view('instructor.course_enrolled', compact(
        'totalEnrollments',
        'activeStudents',
        'moneyFlow',
        'courseStats',
        'monthlyData',
        'studentEnrollments'
    ));
}

public function courseDetails($courseId)
{
    $course = Course::where('user_id', Auth::id())
        ->with(['enrolledUsers' => function($query) {
            $query->select('users.id', 'users.name', 'users.email', 'user_courses.progress', 'user_courses.last_accessed_at');
        }])
        ->findOrFail($courseId);

    return view('instructor.course_details', compact('course'));
}

}
