<?php

// Import controllers and models
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\InstructorProfileController;
use App\Http\Controllers\Instructor\QuizQuestionController;
use App\Models\InstructorRequest;
use App\Http\Controllers\QuizResultController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaskSubmissionController;
use App\Http\Controllers\MentorBotController;
use App\Http\Controllers\InsightQuestController;
use App\Http\Controllers\InsightQuestSubmissionController;

/*
|--------------------------------------------------------------------------
| Main Page & Home Routes
|--------------------------------------------------------------------------
*/

// Home page route
Route::get('/', [HomeController::class, 'Home']);

// Dashboard route for verified authenticated users
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/stats', [HomeController::class, 'getDashboardStats'])->name('dashboard.stats');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (requires authentication)
|--------------------------------------------------------------------------
*/

// Routes to manage user profile (edit, update, delete)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'setting'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (requires authentication, email verification, and admin middleware)
|--------------------------------------------------------------------------
*/

// Routes for admin panel functions (dashboard, manage users, etc.)
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/update-usertype', [AdminController::class, 'updateUserType'])->name('admin.updateUserType');
    Route::post('/admin/deleteUser', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::get('/admin/pending-courses', [AdminController::class, 'pendingCourses'])->name('admin.pending_courses');
    Route::get('/admin/course/{id}', [AdminController::class, 'showCourse'])->name('admin.incourse');
    Route::post('/admin/course/approve/{id}', [AdminController::class, 'approveCourse'])->name('admin.approve.course');
    Route::post('/admin/course/reject/{id}', [AdminController::class, 'rejectCourse'])->name('admin.reject.course');
});

/*
|--------------------------------------------------------------------------
| Google Authentication Routes
|--------------------------------------------------------------------------
*/

// Routes for Google authentication
Route::get('auth/google', [GoogleController::class, 'googlepage']);
Route::get('auth/google/callback', [GoogleController::class, 'googlecallback']);

/*
|--------------------------------------------------------------------------
| Breeze Authentication Routes
|--------------------------------------------------------------------------
*/

// Include Breeze authentication routes
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Instructor Routes (requires authentication and instructor middleware)
|--------------------------------------------------------------------------
*/

// Routes for instructor functionalities
Route::middleware(['auth', 'instructor'])->group(function () {
    // Dashboard for instructor
    Route::get('instructor/dashboard', [InstructorController::class, 'index'])
    ->name('instructor.dashboard');

    // Notifications route
    Route::get('instructor/notifications', [App\Http\Controllers\Instructor\NotificationController::class, 'index'])
    ->name('instructor.notifications.index');

    // Category Routes
    Route::get('view_category', [InstructorController::class, 'view_category']);

    Route::get('add_category', [InstructorController::class, 'create'])->name('instructor.category.create');
    Route::post('add_category', [InstructorController::class, 'store'])->name('instructor.category.store');
    Route::get('delete_category/{id}', [InstructorController::class, 'delete_category'])->name('instructor.category.delete');
    Route::get('edit_category/{id}', [InstructorController::class, 'edit_category'])->name('instructor.category.edit');
    Route::post('update_category/{id}', [InstructorController::class, 'update_category'])->name('instructor.category.update');
    Route::get('search_category', [InstructorController::class, 'searchCategory'])->name('instructor.category.search');

    // Course Routes
    Route::get('add_course', [InstructorController::class, 'add_course']);
    Route::post('upload_course', [InstructorController::class, 'upload_course']);
    Route::get('view_course', [InstructorController::class, 'view_course']);
    Route::get('delete_course/{id}', [InstructorController::class, 'delete_course'])->name('delete_course');
    Route::get('update_course/{id}', [InstructorController::class, 'update_course'])->name('update_course');
    Route::post('update_course/{id}', [InstructorController::class, 'updateCourse'])->name('update_course');
    Route::get('search_course', [InstructorController::class, 'searchCourse'])->name('search_course');

    // Chapter Routes
    Route::get('add-chapter', [InstructorController::class, 'showAddChapterForm'])->name('show.add.chapter');
    Route::post('add-chapter', [InstructorController::class, 'storeChapter'])->name('store.chapter');
    Route::get('/view_chapters', [InstructorController::class, 'viewChapters'])->name('view_chapters');
    Route::get('delete_chapter/{id}', [InstructorController::class, 'delete_chapter'])->name('delete_chapter');
    Route::get('update_chapter/{id}', [InstructorController::class, 'update_chapter'])->name('update_chapter');
    Route::post('update_chapter/{id}', [InstructorController::class, 'updateChapter'])->name('updateChapter');
    Route::get('search_chapter', [InstructorController::class, 'searchChapter'])->name('search_chapter');

    // Lesson Routes
    Route::get('add-lesson', [InstructorController::class, 'showAddLessonForm'])->name('add.lesson');
    Route::post('add-lesson', [InstructorController::class, 'storeLesson'])->name('store.lesson');
    Route::get('view-lessons', [InstructorController::class, 'viewLessons'])->name('view.lessons');
    Route::get('/view_lessons', [InstructorController::class, 'viewLessons'])->name('view_lessons');
    Route::get('delete_lesson/{id}', [InstructorController::class, 'delete_lesson'])->name('delete_lesson');
    Route::get('update_lesson/{id}', [InstructorController::class, 'update_lesson'])->name('update_lesson');
    Route::post('update_lesson/{id}', [InstructorController::class, 'updateLesson'])->name('updateLesson');
    Route::get('view-chapters', [InstructorController::class, 'viewChapters'])->name('view.chapters');
    Route::get('search_lesson', [InstructorController::class, 'searchLesson'])->name('search_lesson');

    // Tasks Routes
    Route::get('/tasks/add', [TasksController::class, 'create'])->name('add.task');
    Route::post('/tasks/store', [TasksController::class, 'store'])->name('store.task');
    Route::get('/tasks', [TasksController::class, 'index'])->name('view.tasks');
    Route::get('/tasks/{task}/edit', [TasksController::class, 'edit'])->name('edit.task');
    Route::put('/tasks/{task}/update', [TasksController::class, 'update'])->name('update.task');
    Route::delete('/tasks/{task}/delete', [TasksController::class, 'destroy'])->name('delete.task');

    // Task Point Routes (Separate Add)
    Route::get('/tasks/points/add', [TasksController::class, 'createPoint'])->name('add.task.point'); // Show form to add points
    Route::post('/tasks/points/store', [TasksController::class, 'storePoint'])->name('store.task.point'); // Store the added points

    // AJAX Route
    Route::get('/get-chapters/{courseId}', [TasksController::class, 'getChapters'])->name('get.chapters'); // Keep this

    // Task Submissions Routes
    Route::get('/submissions', [InstructorController::class, 'viewAllSubmissions'])->name('task.all.submissions');
    Route::post('/submission/{submission}/review', [InstructorController::class, 'reviewSubmission'])->name('task.review');

    // Course Enrollment Analysis Routes
    Route::get('/instructor/course-enrollment-analysis', [InstructorController::class, 'courseEnrollmentAnalysis'])
        ->name('instructor.course.enrolled')
        ->middleware(['auth', 'instructor']);

    Route::get('/instructor/course/{course}/details', [InstructorController::class, 'courseDetails'])
        ->name('instructor.course.details')
        ->middleware(['auth', 'instructor']);
});

/*
|--------------------------------------------------------------------------
| Courses Home & Content Routes (Requires Auth for Some Routes)
|--------------------------------------------------------------------------
*/

// Public route for courses page
Route::get('/courses_page', [HomeController::class, 'courses_page'])->name('courses_page');
Route::get('/courses', [HomeController::class, 'courses_page'])->name('courses');
Route::get('/courses/search', [HomeController::class, 'searchCourses'])->name('courses.search');
Route::get('/live-search-courses', [HomeController::class, 'liveSearchCourses'])->name('courses.liveSearch');
Route::get('/courses/category/{id}', [HomeController::class, 'coursesByCategory'])->name('courses.byCategory');
Route::get('/categories', [HomeController::class, 'getCategories'])->name('categories.get');
Route::get('/courses/load-more', [HomeController::class, 'loadMoreCourses'])->name('courses.loadMore');

// Routes for course details and content, some require authentication
Route::middleware('auth')->group(function () {
    Route::get('incourse/{id}', [HomeController::class, 'incourse'])
        ->name('incourse');
        
    Route::get('course-content/{id}', [HomeController::class, 'courseContent'])
        ->name('course.content')
        ->middleware(['course.purchased']);
    Route::get('/task/{task}', [HomeController::class, 'showTask'])
        ->name('task.show')
        ->middleware(['course.purchased']);
    
    // Add route for getting user's courses
    Route::get('/get-user-courses', [HomeController::class, 'getUserCourses'])->name('get.user.courses');
    Route::get('/get-next-task/{courseId}', [HomeController::class, 'getNextTask'])->name('get.next.task');
    Route::get('/course/{courseId}/lesson-statistics', [HomeController::class, 'getCourseLessonStatistics'])->name('course.lesson.statistics');
});

/*
|--------------------------------------------------------------------------
| Payment Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/

// Routes for payment and course enrollment
Route::middleware('auth')->group(function () {
    Route::get('payment/{id}', [EnrollmentController::class, 'payment'])->name('payment');

    Route::controller(EnrollmentController::class)->group(function () {
        Route::get('stripe/{id}', [EnrollmentController::class, 'stripe']);
        Route::post('stripe/{id}', [EnrollmentController::class, 'stripePost'])->name('stripe.post');
    });

    // Route to show purchased courses
    Route::get('/purchased-courses', [EnrollmentController::class, 'showPurchasedCourses'])->name('purchased.courses');
    // Route to store lesson progress
    Route::post('store-lesson-progress', [HomeController::class, 'storeLessonProgress'])->name('store.lesson.progress');
});

/*
|--------------------------------------------------------------------------
| Proflie Settings Routes
|--------------------------------------------------------------------------
*/

// Test route for authenticated users
Route::middleware('auth')->get('/test', [HomeController::class, 'test']);

// Duplicate profile update route (kept as is)
Route::patch('/profile', [ProfileController::class, 'setting'])->name('profile.update');

// Admin profile management routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
});

// Instructor profile management routes
Route::middleware(['auth',])->group(function () {
    // Display the instructor profile edit page
    Route::get('/instructor/profile', [InstructorProfileController::class, 'edit'])
        ->name('instructor.profile.edit');

    // Process the instructor profile update
    Route::patch('/instructor/profile', [InstructorProfileController::class, 'update'])
        ->name('instructor.profile.update');
});


/*
|--------------------------------------------------------------------------
| Requested Instructor Access Routes
|--------------------------------------------------------------------------
*/

// Routes for instructor access requests
Route::post('request_instructor_access', [HomeController::class, 'requestInstructorAccess'])->name('request_instructor_access');
Route::post('admin/processInstructorRequest', [AdminController::class, 'processInstructorRequest'])->name('admin.processInstructorRequest');

// Routes for admin to manage pending instructor requests
Route::get('admin/pending-requests', [AdminController::class, 'pendingRequests'])->name('admin.pendingRequests');
Route::post('admin/process-instructor-request', [AdminController::class, 'processInstructorRequest'])->name('admin.processInstructorRequest');

/*
|--------------------------------------------------------------------------
| Quiz Routes
|--------------------------------------------------------------------------
*/
// Quiz routes grouped under the "instructor" prefix for organization.
Route::prefix('instructor')->group(function () {
    Route::get('/quizzes', [QuizController::class, 'index'])->name('instructor.quizzes.index');
    Route::get('/quizzes/create', [QuizController::class, 'createWithDropdown'])->name('instructor.quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('instructor.quizzes.store');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('instructor.quizzes.show');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('instructor.quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('instructor.quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('instructor.quizzes.destroy');
    Route::get('/instructor/quizzes/search', [QuizController::class, 'search']) ->name('instructor.quizzes.search');
   
    // Get chapters for quiz purposes
    Route::get('/get-chapters', [InstructorController::class, 'getChapters'])->name('instructor.getChapters');
});

/*
|--------------------------------------------------------------------------
| Quiz Question Routes
|--------------------------------------------------------------------------
*/
// Quiz question routes separated for clarity.
Route::get('quizquestions/create', [App\Http\Controllers\QuizQuestionController::class, 'create'])->name('quizquestions.create');
Route::post('quizquestions/store', [App\Http\Controllers\QuizQuestionController::class, 'store'])->name('quizquestions.store');
Route::get('quizquestions/view', [App\Http\Controllers\QuizQuestionController::class, 'index'])->name('quizquestions.view');
Route::get('quizquestions/edit/{id}', [App\Http\Controllers\QuizQuestionController::class, 'edit'])->name('quizquestions.edit');
Route::put('quizquestions/update/{id}', [App\Http\Controllers\QuizQuestionController::class, 'update'])->name('quizquestions.update');
Route::delete('quizquestions/destroy/{id}', [App\Http\Controllers\QuizQuestionController::class, 'destroy'])->name('quizquestions.destroy');
Route::get('view_quiz_questions', [App\Http\Controllers\QuizQuestionController::class, 'index'])->name('view.quiz.questions');
Route::get('/quizquestions/search', [App\Http\Controllers\QuizQuestionController::class, 'search'])->name('quizquestions.search');





/*
|--------------------------------------------------------------------------
| Noftications INSTRACTOUR Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth',])->group(function () {
    Route::post('/instructor/notifications/mark-read/{id}', [InstructorController::class, 'markNotificationAsRead'])
    ->name('instructor.notifications.markRead');

    Route::get('/instructor/notifications', [InstructorController::class, 'notifications'])
    ->name('instructor.notifications');

    Route::post('/instructor/notifications/mark-unread/{id}', [InstructorController::class, 'markNotificationAsUnread'])
    ->name('instructor.notifications.markUnread');

    Route::post('/instructor/notifications/mark-all-read', [InstructorController::class, 'markAllRead'])
    ->name('instructor.notifications.markAllRead');

});

  
/*
|--------------------------------------------------------------------------
| Noftications Admin Routes
|--------------------------------------------------------------------------
*/

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');

        Route::post('/admin/notifications/mark-read/{id}', [AdminController::class, 'markNotificationAsRead'])
            ->name('admin.notifications.markRead');

        Route::post('/admin/notifications/mark-unread/{id}', [AdminController::class, 'markNotificationAsUnread'])
            ->name('admin.notifications.markUnread');

            Route::post('/admin/notifications/mark-all-read', [AdminController::class, 'markAllRead'])->name('admin.notifications.markAllRead');
    });
    


    Route::middleware('auth')->group(function () {
        Route::get('/search/lessons', [HomeController::class, 'searchLessons'])->name('lessons.search');
        Route::get('/quiz/{quiz}', [HomeController::class, 'showQuiz'])->name('quiz.show');
        Route::post('/quiz-results', [QuizResultController::class, 'store'])->name('quizresults.store');
        Route::delete('/quiz/{quiz}/deletePrevious', [QuizResultController::class, 'deletePrevious'])
    ->name('quizresults.deletePrevious')
    ->middleware('auth');

    });
    Route::post('/mark-lesson-watched/{lesson}', [HomeController::class, 'markLessonWatched']);



    // Course Content Page (Shows lessons, quizzes, tasks sidebar)
    Route::get('/course/{id}/content', [HomeController::class, 'courseContent'])
         ->name('course.content')
         ->middleware(['course.purchased']); // {id} is the Course ID

    // Route to display a specific Task
    // Uses Route Model Binding: {task} is the Task ID, Laravel finds the Task model
    Route::get('/task/{task}', [HomeController::class, 'showTask'])
         ->name('task.show');

// Task Submissions
Route::middleware(['auth'])->group(function () {
    Route::post('/task/{task}/submit', [TaskSubmissionController::class, 'submit'])->name('task.submit');
    Route::get('/my-submissions', [TaskSubmissionController::class, 'getUserSubmissions'])->name('user.submissions');
    Route::get('/check-task-submission/{task}', [TaskSubmissionController::class, 'checkSubmission'])->name('task.check.submission');
    Route::get('/quiz-statistics', [HomeController::class, 'getQuizStatistics'])->name('quiz.statistics');
    Route::get('/course/{course}/quiz-statistics', [HomeController::class, 'getCourseQuizStatistics'])->name('course.quiz.statistics');
    Route::get('/course/{course}/task-statistics', [HomeController::class, 'getCourseTaskStatistics'])->name('course.task.statistics');
});

// Instructor Routes
Route::middleware(['auth', 'instructor'])->group(function () {
    Route::post('/submission/{submission}/review', [TaskSubmissionController::class, 'review'])->name('task.review');
    Route::get('/task/{task}/submissions', [TaskSubmissionController::class, 'getSubmissionsByTask'])->name('task.submissions');
    Route::get('/course/{course}/submissions', [TaskSubmissionController::class, 'getSubmissionsByCourse'])->name('course.submissions');
});

Route::get('/task-submission/{submission}/file/{file}/download', [App\Http\Controllers\TaskSubmissionController::class, 'downloadSubmissionFile'])
    ->name('task.submission.file.download')
    ->middleware('auth');

Route::get('/instructor/quiz-attempts', [App\Http\Controllers\InstructorQuizController::class, 'showAttempts'])->name('instructor.quiz.attempts');


Route::prefix('instructor')->middleware(['auth', 'instructor'])->group(function () {
    // Insight Quest Routes
    Route::get('/insight-quest/add', [InsightQuestController::class, 'create'])->name('insight_quest.add');
    Route::post('/insight-quest/store', [InsightQuestController::class, 'store'])->name('insight_quest.store');
    Route::get('/insight-quest/view', [InsightQuestController::class, 'index'])->name('insight_quest.view');
    Route::get('/insight-quest/edit/{id}', [InsightQuestController::class, 'edit'])->name('insight_quest.edit');
    Route::put('/insight-quest/update/{id}', [InsightQuestController::class, 'update'])->name('insight_quest.update');
    Route::delete('/insight-quest/delete/{id}', [InsightQuestController::class, 'destroy'])->name('insight_quest.delete');
    Route::get('/insight-quest/search', [InsightQuestController::class, 'search'])->name('insight_quest.search');
    
    // Insight Quest Submission Routes
    Route::get('/insight-quest/submissions', [InsightQuestSubmissionController::class, 'allSubmissions'])->name('insight_quest.submissions');
    Route::post('/insight-quest/submission/{submission}/review', [InsightQuestSubmissionController::class, 'review'])->name('insight_quest.review');
});

Route::post('/insight-quest/{quest}/upload', [InsightQuestSubmissionController::class, 'upload'])->name('insight_quest.upload');

Route::get('/download/insight-quest/{file}', [InsightQuestSubmissionController::class, 'download'])->name('insight_quest.download');

Route::get('/insight-quest/{quest}/submission-status', [InsightQuestSubmissionController::class, 'showSubmissionStatus'])->name('insight_quest.submission_status');

Route::post('/notifications/{id}/mark-read', [HomeController::class, 'markNotificationAsRead'])->name('notifications.markRead');

// Admin Detail Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Route::get('/users/details', function () {
    //     return view('admin.users_details');
    // })->name('admin.users.details');

    Route::get('/courses/details', function () {
        return view('admin.courses_details');
    })->name('admin.courses.details');

    Route::get('/revenue/details', function () {
        return view('admin.revenue_details');
    })->name('admin.revenue.details');

    Route::get('/students/details', function () {
        return view('admin.students_details');
    })->name('admin.students.details');
});

Route::get('/generate-certificate/{courseId}', [HomeController::class, 'generateCertificate'])
    ->middleware(['auth'])
    ->name('generate.certificate');

Route::get('/check-all-lessons-watched/{courseId}', [App\Http\Controllers\CourseController::class, 'checkAllLessonsWatched']);




