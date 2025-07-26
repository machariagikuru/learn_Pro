@extends('instructor.layout_instructor')

@section('body')
<div class="container mt-4">
  <!-- باقي كروت الداشبورد -->
  <div class="row g-4">
    <!-- New Clients Card -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title mb-0">New Clients</h5>
            <p class="card-text fs-4">
              {{
                DB::table('user_courses')
                  ->join('courses', 'user_courses.course_id', '=', 'courses.id')
                  ->where('courses.user_id', auth()->user()->id)
                  ->count()
              }}
            </p>
          </div>
          <div class="icon text-primary">
            <i class="fas fa-user fa-2x"></i>
          </div>
        </div>
        <div class="progress" style="height: 4px;">
          <div class="progress-bar bg-primary" role="progressbar" style="width: 30%"></div>
        </div>
      </div>
    </div>
    
    <!-- New Projects Card -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title mb-0">User Complete Courses</h5>
            <p class="card-text fs-4">
              {{
                DB::table('user_courses')
                  ->join('courses', 'user_courses.course_id', '=', 'courses.id')
                  ->where('courses.user_id', auth()->user()->id)
                  ->whereNotExists(function ($query) {
                      $query->select(DB::raw(1))
                          ->from('lessons')
                          ->join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
                          ->whereRaw('chapters.course_id = user_courses.course_id')
                          ->whereNotExists(function ($subquery) {
                              $subquery->select(DB::raw(1))
                                  ->from('lesson_user')
                                  ->whereRaw('lesson_user.lesson_id = lessons.id')
                                  ->whereRaw('lesson_user.user_id = user_courses.user_id');
                          });
                  })
                  ->distinct('user_courses.user_id')
                  ->count('user_courses.user_id')
              }}
            </p>
          </div>
          <div class="icon text-success">
            <i class="fas fa-project-diagram fa-2x"></i>
          </div>
        </div>
        <div class="progress" style="height: 4px;">
          <div class="progress-bar bg-success" role="progressbar" style="width: 70%"></div>
        </div>
      </div>
    </div>
    
    <!-- New Invoices Card -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title mb-0">Monney Flow</h5>
            <p class="card-text fs-4">
              {{
                DB::table('user_courses')
                  ->join('courses', 'user_courses.course_id', '=', 'courses.id')
                  ->where('courses.user_id', auth()->user()->id)
                  ->sum('courses.price') * 0.8
              }} $
            </p>
          </div>
          <div class="icon text-warning">
            <i class="fas fa-file-invoice-dollar fa-2x"></i>
          </div>
        </div>
        <div class="progress" style="height: 4px;">
          <div class="progress-bar bg-warning" role="progressbar" style="width: 55%"></div>
        </div>
      </div>
    </div>
    
    <!-- All Projects Card -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title mb-0">Overall Courses</h5>
            <p class="card-text fs-4">
              {{ \App\Models\Course::count() }}
            </p>
          </div>
          <div class="icon text-danger">
            <i class="fas fa-tasks fa-2x"></i>
          </div>
        </div>
        <div class="progress" style="height: 4px;">
          <div class="progress-bar bg-danger" role="progressbar" style="width: 35%"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- باقي الكروت -->
  <div class="container mt-4">
    <div class="row g-4">
      <!-- Course Category Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
              <h5 class="card-title">Course Category</h5>
              <p class="card-text fs-4">{{ \App\Models\Category::count() }}</p>
            <div class="dropdown">
              <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('instructor.category.create') }}">Add Category</a></li>
                <li><a class="dropdown-item" href="{{ url('view_category') }}">View Category</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Courses Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Courses</h5>
              <p class="card-text fs-4">
              {{
                \App\Models\Course::where('user_id', auth()->user()->id)->count()
              }}
              </p>
            <div class="dropdown">
              <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ url('add_course') }}">Add Course</a></li>
                <li><a class="dropdown-item" href="{{ url('view_course') }}">View Courses</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Chapters Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
              <h5 class="card-title">Chapters</h5>
              <p class="card-text fs-4">
              {{
                \App\Models\Chapter::whereHas('course', function ($query) {
                $query->where('user_id', auth()->user()->id);
                })->count()
              }}
              </p>
            <div class="dropdown">
              <button class="btn btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('show.add.chapter') }}">Add Chapter</a></li>
                <li><a class="dropdown-item" href="{{ route('view.chapters') }}">View Chapters</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Lessons Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Lessons</h5>
            <p class="card-text fs-4">
              {{ \App\Models\Lesson::whereHas('chapter.course', function ($query) {
                $query->where('user_id', auth()->user()->id);
                })->count() }}
            </p>
            <div class="dropdown">
              <button class="btn btn-outline-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('add.lesson') }}">Add Lesson</a></li>
                <li><a class="dropdown-item" href="{{ route('view.lessons') }}">View Lessons</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Quizzes Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Quizzes</h5>
            <p class="card-text fs-4">
              {{ \App\Models\Quiz::whereHas('chapter.course', function ($query) {
                  $query->where('user_id', auth()->user()->id);
              })->count() }}
            </p>
            <div class="dropdown">
              <button class="btn btn-outline-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('instructor.quizzes.create') }}">Add Quiz</a></li>
                <li><a class="dropdown-item" href="{{ route('instructor.quizzes.index') }}">View Quizzes</a></li>
                <li><a class="dropdown-item" href="{{ route('instructor.quiz.attempts') }}">View Quiz Attempts</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Quiz Questions Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Quiz Questions</h5>
            <p class="card-text fs-4">
              {{ \App\Models\QuizQuestion::whereHas('quiz.chapter.course', function ($query) {
                  $query->where('user_id', auth()->user()->id);
              })->count() }}
            </p>
            <div class="dropdown">
              <button class="btn btn-outline-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="{{ route('quizquestions.create') }}">Add Quiz Question</a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('quizquestions.view') }}">View Quiz Questions</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- Tasks Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Tasks </h5>
            <p class="card-text fs-4">
                {{ \App\Models\Task::whereHas('chapter.course', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                })->count() }}
            </p>
            <div class="dropdown">
              <button class="btn btn-outline-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="{{ route('add.task') }}">Add Task</a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('view.tasks') }}">View Tasks</a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('task.all.submissions') }}">View Submissions</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Insight Quest Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Insight Quest</h5>
            <p class="card-text fs-4">
                {{ \App\Models\InsightQuest::where('user_id', auth()->user()->id)->count() }}
            </p>
            <div class="dropdown">
              <button class="btn btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="{{ route('insight_quest.add') }}">Add Quest</a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('insight_quest.view') }}">View Quests</a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('insight_quest.submissions') }}">View Submissions</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Course Enrollment Analysis Card -->
      <div class="col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Course Enrollment Analysis</h5>
            <p class="card-text fs-4">
              {{
                DB::table('user_courses')
                  ->join('courses', 'user_courses.course_id', '=', 'courses.id')
                  ->where('courses.user_id', auth()->user()->id)
                  ->count()
              }}
            </p>
            <div class="dropdown">
              <button class="btn btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('instructor.course.enrolled') }}">View Analysis</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    console.log("Bootstrap and dropdowns are loaded correctly.");
  });
</script>

@endsection
