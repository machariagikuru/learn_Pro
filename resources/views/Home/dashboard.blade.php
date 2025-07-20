@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mt-5">
    <!-- Statistics Card -->
    <div class="col-md-3">
        <div class="card p-4 stats-card">
            <!-- Header with title and arrow -->
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold">Statistics</h5>
                <div class="dropdown">
                    <button class="btn btn-link text-dark p-0" type="button" id="courseDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="courseDropdown">
                        <li><h6 class="dropdown-header">Select Course</h6></li>
                        <li><div id="courseList" class="px-3 py-2">
                            <select class="form-select form-select-sm" id="courseSelect">
                                <option value="">All Courses</option>
                            </select>
                        </div></li>
                    </ul>
                </div>
            </div>

            <!-- Circular Progress Bar -->
            <div class="circle-progress">
                <svg width="140" height="140" viewBox="0 0 100 100">
                    <!-- Background Circle -->
                    <circle cx="50" cy="50" r="45" stroke="#E5E7EB" stroke-width="8" fill="none"></circle>
                    <!-- Progress Circle -->
                    <circle class="progress-bar" cx="50" cy="50" r="45" stroke="#007bff" stroke-width="8" fill="none"
                        stroke-dasharray="282.6" stroke-dashoffset="282.6"></circle>
                </svg>
                <div class="progress-text">
                    <span class="percentage" id="overallProgress">0%</span>
                    <p class="text-muted">Overall Progress</p>
                </div>
            </div>

            <div class="stats-breakdown">
                <div class="stat-item">
                    <p class="text-muted">Tasks</p>
                    <span class="text-primary fw-bold" id="tasksPercentage">Loading...</span>
                </div>
                <div class="stat-separator"></div>
                <div class="stat-item">
                    <p class="text-muted">Quizes</p>
                    <span class="text-primary fw-bold" id="quizzesPercentage">Loading...</span>
                </div>
                <div class="stat-separator"></div>
                <div class="stat-item">
                    <p class="text-muted">Viewing rate</p>
                    <span class="text-primary fw-bold" id="viewingPercentage">Loading...</span>
                </div>
            </div>

            <!-- More Detail Button -->
            <div class="mt-3">
                <button id="showStatsModal" class="btn btn-primary w-100 fw-bold">More Detail</button>
            </div>

            <!-- Statistics Modal -->
            <div id="statsModal" class="modal fade" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content stats-modal p-4">
                        <!-- Modal Header -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="modal-title fw-bold">Statistics</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Statistics Type and Course Selection -->
                        <div class="row mt-4 mb-3">
                            <div class="col-md-6">
                                <select class="form-select" id="statsTypeSelect">
                                    <option value="quiz">Quiz Statistics</option>
                                    <option value="task">Task Statistics</option>
                                    <option value="lesson">Lesson Watched</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="statsCourseSelect">
                                    <option value="">Select Course</option>
                                </select>
                            </div>
                        </div>

                        <!-- Knowledge Gross Graph -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Performance Progress</h5>
                            </div>
                            <div class="chart-container" style="position: relative; height: 200px;">
                                <canvas id="knowledgeGrossChart"></canvas>
                            </div>
                        </div>

                        <!-- Quizzes and Average Marks -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="mb-3" id="leftStatsLabel">Attempted Quizzes</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="circular-progress" style="width: 120px; height: 120px;">
                                                <canvas id="quizzesChart"></canvas>
                                            </div>
                                            <div class="text-end">
                                                <h3 class="mb-0 quizzes-count">0</h3>
                                                <p class="text-muted mb-0" id="leftStatsDescription">Attempted Quizzes</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="mb-3" id="rightStatsLabel">Average Marks</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="circular-progress" style="width: 120px; height: 120px;">
                                                <canvas id="averageMarksChart"></canvas>
                                            </div>
                                            <div class="text-end">
                                                <h3 class="mb-0 average-marks">0%</h3>
                                                <p class="text-muted mb-0" id="rightStatsDescription">Average Score</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mentor Bot & Calendar (Same Height) -->
    <div class="col-md-9">
        <div class="row d-flex align-items-stretch">
            <!-- Mentor Bot -->
            <div class="col-md-6 d-flex">
                <div class="card mentor-bot-card flex-fill">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-robot text-primary me-2"></i>
                                    <span class="mentor-title">Mentor Bot</span>
                                </div>
                                <div class="badge bg-primary rounded-pill ms-2">4</div>
                            </div>
                            <div class="message-preview">
                                <div class="message-bubble">
                                Hi there! I'm your Mentor Bot 🤖🤖w . To get started, I'd love to ask you a few quick questions to understand your needs better. Ready?
                                </div>
                            </div>
                        </div>
                        <button id="askMentorBot" class="btn btn-primary w-100 mt-4">Ask</button>
                    </div>
                </div>
            </div>
            <!-- Mentor Bot Modal -->
            <div id="mentorBotModal" class="modal fade" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0">
                        <!-- Modal Header -->
                        <div class="modal-header border-0 bg-white p-4">
                            <div class="d-flex align-items-center w-100">
                                <button type="button" class="btn btn-outline-secondary btn-sm me-3" data-bs-dismiss="modal">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/robot.png" alt="Mentor Bot" class="rounded-circle" width="30" height="30">
                                    <h5 class="modal-title ms-2 mb-0">MENTOR BOT</h5>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body p-4">
                            <!-- Chat Category Pills -->
                            <div class="chat-categories mb-4">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary px-4 py-2 active">UI/UX</button>
                                    <button class="btn btn-light px-4 py-2">Development</button>
                                    <button class="btn btn-light px-4 py-2">Marketing</button>
                                </div>
                            </div>

                            <!-- Chat Messages Area -->
                            <div class="chat-messages" style="height: 400px; overflow-y: auto;">
                               
                            </div>

                            <!-- Chat Input -->
                            <div class="chat-input mt-4">
                                <div class="input-group">
                                    <input type="text" class="form-control border-2 py-3 px-4 rounded-3" 
                                           placeholder="Start typing..." 
                                           style="border-radius: 1rem !important;">
                                    <button class="btn btn-link text-primary px-3" type="button">
                                        <i class="far fa-face-smile fs-5"></i>
                                    </button>
                                    <button class="btn btn-primary px-4" type="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           <!-- Calendar -->
            <div class="col-md-6 d-flex">
                <div class="calendar-card flex-fill">
                    
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row mt-4">
    <!-- Task & Deadline -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm rounded-lg task-card">
            <!-- Card Header -->
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold">Task & Deadline</h5>
                <span class="text-muted fs-4">⋯</span>
            </div>

            @php
                // Get user's enrolled courses
                $userCourses = auth()->user()->courses;

                // Get tasks from enrolled courses
                $tasks = collect();
                    foreach($userCourses as $course) {
                    if ($course && $course->chapters) { // Add null check for course and chapters
                            foreach($course->chapters as $chapter) {
                            if ($chapter && $chapter->tasks) { // Add null check for chapter and tasks
                                    $tasks = $tasks->merge($chapter->tasks);
                            }
                        }
                    }
                }

                // Get pending tasks (no submission or submission not reviewed)
                $pendingTasks = $tasks->filter(function($task) {
                    if (!$task) return false; // Add null check for task
                    $submission = $task->submissions()->where('user_id', auth()->id())->first();
                    return !$submission || $submission->status === 'pending';
                })->sortBy('created_at');

                // Get the next task to work on
                $nextTask = $pendingTasks->first();
            @endphp

            @if($nextTask && $nextTask->chapter && $nextTask->chapter->course) {{-- Added null checks --}}
                <!-- Task Image (if available) -->
                @if($nextTask->chapter->course->image)
                    <img src="{{ asset('courses/' . $nextTask->chapter->course->image) }}"
                         class="img-fluid rounded mb-3"
                         alt="Course Image">
                @endif

                <!-- Task Details -->
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold">Current Task</h6>
                    <a href="{{ route('course.content', $nextTask->chapter->course->id) }}"
                       class="text-primary text-decoration-none">
                        {{ $nextTask->chapter->course->title }}
                    </a>
                </div>

                <!-- Task Points List -->
                <ul class="list-unstyled">
                   @if($nextTask->taskPoints) {{-- Check if taskPoints exists --}}
                        @foreach($nextTask->taskPoints->take(3) as $index => $point)
                            @if($point) {{-- Check if point exists --}}
                                <li class="@if($index < 2) completed-task @else active-task @endif">
                                    <span>{{ $index + 1 }}</span>
                                    @if($index < 2)
                                         {{ Str::limit($point->title ?? 'N/A', 40) }} {  {{-- Added null check for title --}}
                                    @else
                                        {{ Str::limit($point->title ?? 'N/A', 40) }} {{-- Added null check for title --}}
                                    @endif
                                </li>
                            @endif
                        @endforeach
                   @else
                       <li>No task points available.</li>
                   @endif
                </ul>

                <!-- Deadline Section -->
                @php
                    $now = now();
                    $deadline = $nextTask->due_date ? \Carbon\Carbon::parse($nextTask->due_date) : now()->addDays(7); // Ensure deadline is Carbon instance
                    $timeLeft = $now->diffInHours($deadline, false); // Calculate difference in hours
                    $totalDuration = $now->diffInHours($nextTask->created_at ? \Carbon\Carbon::parse($nextTask->created_at) : $now->subDays(7), false); // Example total duration (creation to deadline)
                    $timeElapsed = $now->diffInHours($nextTask->created_at ? \Carbon\Carbon::parse($nextTask->created_at) : $now->subDays(7));
                    $progress = ($totalDuration > 0) ? max(0, min(100, ($timeElapsed / $totalDuration) * 100)) : 0; // Calculate progress based on time elapsed
                @endphp


                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold">Deadline</h6>
                    <span class="@if($timeLeft < 24 && $timeLeft >= 0) text-danger @elseif($timeLeft < 0) text-danger fw-bold @else text-primary @endif fw-bold">
                        @if($timeLeft > 48)
                            {{ ceil($timeLeft / 24) }} Days Left
                        @elseif($timeLeft > 0)
                            {{ $timeLeft }} Hours Left
                        @else
                            Overdue
                        @endif
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="deadline-bar position-relative">
                    <div class="progress">
                        <div class="progress-bar" id="deadline-progress" style="width: {{ $progress }}%"></div>
                    </div>
                    <div id="progress-indicator" style="left: calc({{ $progress }}% - 7px)"></div>
                </div>

                <!-- Go To Detail Button -->
                <button class="btn btn-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#taskDetailModal">
                    Go To Detail
                </button>

            @else
                <div class="text-center py-4">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <h6>All Tasks Completed!</h6>
                    <p class="text-muted">You're all caught up with your tasks.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Task & Deadline Modal -->
    <div id="taskDetailModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-4">
                <!-- Modal Header -->
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="modal-title fw-bold text-primary">Task Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Course Selection -->
                <div class="mt-3">
                     <!-- **** CHANGED ID HERE **** -->
                    <label for="taskCourseSelect" class="form-label">Select Course</label>
                    <select class="form-select" id="taskCourseSelect">
                        <option value="">Choose a course...</option>
                        {{-- Options will be loaded by JavaScript --}}
                    </select>
                </div>

                <!-- Task List Section -->
                <div id="taskListSection" class="mt-4" style="display: none;">
                    <h5 class="fw-bold mb-3">Course Tasks</h5>
                    <div id="taskList" class="list-group">
                        <!-- Tasks will be populated here by JavaScript -->
                        <p class="text-muted text-center">Select a course to view tasks.</p>
                    </div>
                </div>

                <!-- Task Details Section -->
                <div id="taskDetailsSection" class="mt-4" style="display: none;">
                    <h5 id="selectedTaskTitle" class="fw-bold mb-3"></h5>

                    <!-- Task Points Checklist -->
                    <div class="task-points-checklist mb-4">
                        <h6 class="fw-bold">Task Points</h6>
                        <div id="taskPointsList">
                            <!-- Task points will be populated here by JavaScript -->
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div class="upload-section">
                        <h6 class="fw-bold">Submit Task</h6>
                        <form id="taskSubmissionForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="selectedTaskId" name="task_id">

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"
                                        placeholder="Add any relevant notes for your instructor..."></textarea>
                            </div>

                            <div class="upload-box text-center border p-3 rounded">
                                <label for="fileUpload" class="btn btn-outline-primary">
                                    <i class="fas fa-upload me-2"></i>Choose Files
                                </label>
                                <input type="file" id="fileUpload" name="files[]" class="d-none" multiple>
                                <p class="text-muted mt-2 small">Supported formats: JPEG, PNG, GIF, MP4, PDF, PSD, AI, Word, PPT</p>
                                <div id="file-name" class="text-primary mt-1 small"></div>
                            </div>


                            <button type="submit" class="btn btn-success mt-3 w-100">
                                Submit Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Enrolled Courses + Top Courses -->
    <div class="col-md-8 d-flex flex-column gap-4">
        <!-- Enrolled Courses Section -->
        <div class="card p-4 enrolled-courses mb-4">
            <h5 class="fw-bold">Enrolled Courses</h5>
            <div class="row g-4">
                @forelse(auth()->user()->courses as $course)
                <div class="col-md-4 col-sm-6">
                    <div class="course-card">
                    <a href="{{ route('course.content', $course->id) }}" style="text-decoration: none; color: inherit;">
                        <img src="{{ asset('courses/' . $course->image) }}" class="course-image" alt="{{ $course->title }}">
                        <div class="course-content">
                            @isset($course->category)
                            <span class="category"> <!-- Changed to span as it's not a link here -->
                                {{ $course->category->category_name }}
                                </span>
                            @endisset
                        <h6 class="fw-bold mt-1">{{ $course->title }}</h6>
                        <span class="arrow-link">↗️</span> <!-- Changed to span -->
                        </div>
                    </a>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold mb-1">You are not enrolled in any courses yet</h6>
                        <p class="text-muted">Start your learning journey by enrolling in a course!</p>
                        <a href="{{ route('courses_page') }}" class="btn btn-outline-primary mt-2">Browse Courses</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        <!-- Top Courses Section -->
        <div class="card p-4 mb-4" style="background: #f7fafd; border-radius: 20px;">
            <h5 class="fw-bold">Top Courses</h5>
            @if($topCourses->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                    <h6 class="fw-bold mb-1">No top courses yet</h6>
                    <p class="text-muted">Top purchased courses will appear here once users start enrolling.</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table align-middle top-courses-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted" style="width: 60px;">RANK</th>
                            <th class="text-muted">NAME</th>
                            <th class="text-muted" style="width: 80px;">HOUR</th>
                            <th class="text-muted" style="width: 80px;">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCourses as $index => $course)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $index + 1 }}</span>
                                @php
                                    // Example: You need to provide logic for up/down/neutral arrows
                                    $trend = $course->trend ?? 'up'; // 'up', 'down', or 'neutral'
                                @endphp
                                @if($trend === 'up')
                                    <span class="text-success ms-1">&#9650;</span>
                                @elseif($trend === 'down')
                                    <span class="text-danger ms-1">&#9660;</span>
                                @else
                                    <span class="text-secondary ms-1">&#8212;</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('courses/' . $course->image) }}" alt="Course" class="rounded-circle me-2" width="36" height="36" style="object-fit:cover;">
                                    <a href="{{ route('course.content', $course->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $course->title }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                {{ $course->duration ?? 0 }}
                            </td>
                            <td>
                                <a href="{{ route('course.content', $course->id) }}" class="fw-bold text-primary" style="text-decoration: none;">
                                    {{ number_format($course->rate, 1) }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
</div> {{-- Closing content div? Ensure this matches your layout file --}}

{{-- Include Chart.js library --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Include SweetAlert2 library --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Include Bootstrap JS (assuming it's not already included globally in app.blade.php) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> {{-- Example path, adjust as needed --}}

<style>
.loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    display: inline-block;
    margin-right: 5px;
    vertical-align: middle;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    color: #6c757d;
    font-size: 0.9rem;
}

.stats-card {
    transition: all 0.3s ease;
}

.stats-card.loading {
    opacity: 0.7;
    pointer-events: none;
}

.progress-bar {
    transition: stroke-dashoffset 0.5s ease;
}

/* Updated Mentor Bot Styles */
.mentor-bot-card {
    border-radius: 20px;
    border: none;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.mentor-bot-card .card-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
}

.mentor-title {
    font-size: 16px;
    font-weight: 500;
    color: #000000;
}

.mentor-bot-card .badge {
    font-size: 12px;
    padding: 4px 8px;
    font-weight: 500;
    background: #0d6efd !important;
}

.message-preview {
    margin: 16px 0;
}

.message-bubble {
    background: #0d6efd;
    color: white;
    padding: 16px;
    border-radius: 16px;
    font-size: 14px;
    line-height: 1.5;
}

#askMentorBot {
    background: #0d6efd;
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.2s ease;
    margin-top: auto;
}

#askMentorBot:hover {
    background: #0b5ed7;
}

.fa-robot {
    font-size: 18px;
}

/* Mentor Bot Styles */
.chat-categories .btn {
    border-radius: 1rem;
    font-weight: 500;
}

.chat-categories .btn.active {
    background-color: #0d6efd;
    color: white;
}

.message-bubble {
    border-radius: 1rem;
    max-width: 80%;
}

.chat-messages {
    background-color: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
}

.message-content {
    border-radius: 1rem !important;
    max-width: 80%;
}

.bot-message .message-content {
    background-color: #f8f9fa;
}

.user-message .message-content {
    background-color: #0d6efd;
}

.chat-input .form-control {
    border: 2px solid #dee2e6;
    box-shadow: none;
}

.chat-input .form-control:focus {
    border-color: #0d6efd;
    box-shadow: none;
}

.modal-content {
    border-radius: 1.5rem;
}

.modal-header {
    border-top-left-radius: 1.5rem;
    border-top-right-radius: 1.5rem;
}

.badge {
    border-radius: 1rem;
    padding: 0.5em 1em;
}

.top-courses-table {
    background: #f7fafd;
    border-radius: 20px;
    overflow: hidden;
    min-width: 600px;
}
.top-courses-table th, .top-courses-table td {
    background: transparent !important;
    border: none;
    vertical-align: middle;
    font-size: 1rem;
}
.top-courses-table thead th {
    font-weight: 600;
    color: #8a8fa3;
    letter-spacing: 0.03em;
    background: transparent;
}
.top-courses-table tbody tr {
    border-top: 1px solid #e9ecef;
}
.top-courses-table tbody tr:first-child {
    border-top: none;
}
.top-courses-table img {
    border-radius: 50%;
    object-fit: cover;
}
.top-courses-table a.fw-bold.text-primary {
    color: #1976f6 !important;
    font-weight: 700;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
const calendar = document.getElementById("calendar");
calendar.innerHTML = "<p>📅 Calendar will be here</p>";
});
</script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/calendar.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // --- Statistics Modal Logic ---
    const showStatsModalButton = document.getElementById('showStatsModal');
    const statsModalElement = document.getElementById('statsModal');
    const statsCourseSelect = document.getElementById('statsCourseSelect'); // **** Use new ID ****
    const statsTypeSelect = document.getElementById('statsTypeSelect');

    if (showStatsModalButton && statsModalElement && statsCourseSelect && statsTypeSelect) {
        const statsModal = new bootstrap.Modal(statsModalElement);
        let knowledgeGrossChart, quizzesChart, averageMarksChart;
        let currentStatsType = 'quiz';
        let coursesLoaded = false;

        // Load user's courses when modal is about to be shown
        statsModalElement.addEventListener('show.bs.modal', function() {
            if (!coursesLoaded) { // Load only once
                 fetch('/get-user-courses')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            statsCourseSelect.innerHTML = '<option value="">Select Course</option>'; // Clear previous options
                            data.courses.forEach(course => {
                                statsCourseSelect.innerHTML += `<option value="${course.id}">${course.title}</option>`;
                            });
                            coursesLoaded = true; // Mark as loaded
                        } else {
                            console.error("Failed to load courses for stats modal");
                            statsCourseSelect.innerHTML = '<option value="">Error loading courses</option>';
                        }
                    })
                    .catch(error => {
                         console.error("Error fetching courses for stats modal:", error);
                         statsCourseSelect.innerHTML = '<option value="">Error loading courses</option>';
                    });
            }
        });

        showStatsModalButton.addEventListener('click', function() {
            statsModal.show();
        });

        // Handle statistics type change
        statsTypeSelect.addEventListener('change', function() {
            currentStatsType = this.value;
            const courseId = statsCourseSelect.value;
            
            // Update labels based on selected type
            const leftStatsLabel = document.getElementById('leftStatsLabel');
            const rightStatsLabel = document.getElementById('rightStatsLabel');
            const leftStatsDescription = document.getElementById('leftStatsDescription');
            const rightStatsDescription = document.getElementById('rightStatsDescription');

            if (this.value === 'lesson') {
                leftStatsLabel.textContent = 'Lessons Watched';
                rightStatsLabel.textContent = 'Completion Rate';
                leftStatsDescription.textContent = 'Lessons Watched';
                rightStatsDescription.textContent = 'Completion Rate';
            } else if (this.value === 'quiz') {
                leftStatsLabel.textContent = 'Attempted Quizzes';
                rightStatsLabel.textContent = 'Average Marks';
                leftStatsDescription.textContent = 'Attempted Quizzes';
                rightStatsDescription.textContent = 'Average Score';
            } else {
                leftStatsLabel.textContent = 'Submitted Tasks';
                rightStatsLabel.textContent = 'Average Grade';
                leftStatsDescription.textContent = 'Submitted Tasks';
                rightStatsDescription.textContent = 'Average Grade';
            }

            if (courseId) {
                updateStatistics(courseId);
            }
        });

        // Handle course selection change
        statsCourseSelect.addEventListener('change', function() {
            const courseId = this.value;
            if (courseId) {
                updateStatistics(courseId);
            } else {
                // Optionally clear stats if no course is selected
                resetStatsCharts();
            }
        });

        function updateStatistics(courseId) {
            let endpoint;
            switch(currentStatsType) {
                case 'quiz':
                    endpoint = `/course/${courseId}/quiz-statistics`;
                    break;
                case 'task':
                    endpoint = `/course/${courseId}/task-statistics`;
                    break;
                case 'lesson':
                    endpoint = `/course/${courseId}/lesson-statistics`;
                    break;
                default:
                    endpoint = `/course/${courseId}/quiz-statistics`;
            }

            fetch(endpoint)
                .then(response => response.json())
                .then(data => {
                    if(data && typeof data === 'object') {
                        const quizzesCountEl = document.querySelector('.quizzes-count');
                        const averageMarksEl = document.querySelector('.average-marks');
                        const leftStatsLabel = document.getElementById('leftStatsLabel');
                        const rightStatsLabel = document.getElementById('rightStatsLabel');
                        const leftStatsDescription = document.getElementById('leftStatsDescription');
                        const rightStatsDescription = document.getElementById('rightStatsDescription');

                        if (currentStatsType === 'lesson') {
                            if(quizzesCountEl) quizzesCountEl.textContent = data.watched_lessons ?? 0;
                            if(averageMarksEl) averageMarksEl.textContent = (data.completion_percentage ?? 0) + '%';
                            if(leftStatsLabel) leftStatsLabel.textContent = 'Lessons Watched';
                            if(rightStatsLabel) rightStatsLabel.textContent = 'Completion Rate';
                            if(leftStatsDescription) leftStatsDescription.textContent = 'Lessons Watched';
                            if(rightStatsDescription) rightStatsDescription.textContent = 'Completion Rate';
                        } else if (currentStatsType === 'quiz') {
                            if(quizzesCountEl) quizzesCountEl.textContent = data.total_attempts ?? 0;
                            if(averageMarksEl) averageMarksEl.textContent = (data.average_marks ?? 0) + '%';
                            if(leftStatsLabel) leftStatsLabel.textContent = 'Attempted Quizzes';
                            if(rightStatsLabel) rightStatsLabel.textContent = 'Average Marks';
                            if(leftStatsDescription) leftStatsDescription.textContent = 'Attempted Quizzes';
                            if(rightStatsDescription) rightStatsDescription.textContent = 'Average Score';
                        } else {
                            if(quizzesCountEl) quizzesCountEl.textContent = data.total_attempts ?? 0;
                            if(averageMarksEl) averageMarksEl.textContent = (data.average_grade ?? 0) + '%';
                            if(leftStatsLabel) leftStatsLabel.textContent = 'Submitted Tasks';
                            if(rightStatsLabel) rightStatsLabel.textContent = 'Average Grade';
                            if(leftStatsDescription) leftStatsDescription.textContent = 'Submitted Tasks';
                            if(rightStatsDescription) rightStatsDescription.textContent = 'Average Grade';
                        }

                        // Ensure charts are initialized before updating
                        initializeStatsCharts();

                        // Update Knowledge Gross Chart
                        if (knowledgeGrossChart) {
                            knowledgeGrossChart.data.datasets[0].data = data.monthly_progress || Array(12).fill(0);
                            knowledgeGrossChart.data.datasets[0].label = currentStatsType === 'lesson' 
                                ? 'Lessons Watched'
                                : currentStatsType === 'quiz'
                                    ? 'Quiz Progress'
                                    : 'Task Progress';
                            knowledgeGrossChart.update();
                        }

                        // Update Progress Chart
                        if (quizzesChart) {
                            let completed, total;
                            if (currentStatsType === 'lesson') {
                                completed = data.watched_lessons ?? 0;
                                total = data.total_lessons ?? 0;
                            } else {
                                completed = data.total_attempts ?? 0;
                                total = 30; // Default for quiz/task
                            }
                            quizzesChart.data.datasets[0].data = [completed, Math.max(total - completed, 0)];
                            quizzesChart.update();
                        }

                        // Update Average Chart
                        if (averageMarksChart) {
                            const value = currentStatsType === 'lesson'
                                ? data.completion_percentage ?? 0
                                : currentStatsType === 'quiz'
                                    ? data.average_marks ?? 0
                                    : data.average_grade ?? 0;
                            averageMarksChart.data.datasets[0].data = [value, 100 - value];
                            averageMarksChart.update();
                        }
                    } else {
                        console.error("Received invalid data for statistics:", data);
                        resetStatsCharts();
                    }
                })
                .catch(error => {
                    console.error("Error fetching statistics:", error);
                    resetStatsCharts();
                });
        }

        function initializeStatsCharts() {
            // Initialize Knowledge Gross Chart if not already
            const knowledgeGrossCtx = document.getElementById('knowledgeGrossChart')?.getContext('2d');
            if (knowledgeGrossCtx && !knowledgeGrossChart) {
                knowledgeGrossChart = new Chart(knowledgeGrossCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Progress',
                            data: Array(12).fill(0),
                            borderColor: '#007bff',
                            tension: 0.4,
                            fill: false
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, min: 0, max: 100 } }, plugins: { legend: { display: false } } }
                });
            }

            // Initialize Progress Chart if not already
            const quizzesCtx = document.getElementById('quizzesChart')?.getContext('2d');
             if (quizzesCtx && !quizzesChart) {
                quizzesChart = new Chart(quizzesCtx, {
                    type: 'doughnut',
                    data: { datasets: [{ data: [0, 30], backgroundColor: ['#007bff', '#e9ecef'], borderWidth: 0 }] }, // Initial empty state
                    options: { cutout: '75%', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } } }
                });
            }

            // Initialize Average Chart if not already
            const averageMarksCtx = document.getElementById('averageMarksChart')?.getContext('2d');
            if (averageMarksCtx && !averageMarksChart) {
                 averageMarksChart = new Chart(averageMarksCtx, {
                    type: 'doughnut',
                    data: { datasets: [{ data: [0, 100], backgroundColor: ['#28a745', '#e9ecef'], borderWidth: 0 }] }, // Initial empty state
                    options: { cutout: '75%', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } } }
                });
            }
        }

         function resetStatsCharts() {
            // Reset displayed numbers
            const quizzesCountEl = document.querySelector('.quizzes-count');
            const averageMarksEl = document.querySelector('.average-marks');
            if(quizzesCountEl) quizzesCountEl.textContent = '0';
            if(averageMarksEl) averageMarksEl.textContent = '0%';

            // Reset charts to initial state
            if (knowledgeGrossChart) {
                 knowledgeGrossChart.data.datasets[0].data = Array(12).fill(0);
                 knowledgeGrossChart.update();
            }
             if (quizzesChart) {
                 quizzesChart.data.datasets[0].data = [0, 30]; // Or whatever the default max is
                 quizzesChart.update();
            }
             if (averageMarksChart) {
                 averageMarksChart.data.datasets[0].data = [0, 100];
                 averageMarksChart.update();
            }
         }

         // Initial setup or call when modal opens
         initializeStatsCharts();

    } else {
        console.warn("Statistics modal elements not found.");
    }


    // --- Task Deadline Progress Bar ---
    const progressBar = document.getElementById("deadline-progress");
    const progressIndicator = document.getElementById("progress-indicator");
    if (progressBar && progressIndicator) {
        // Get percentage from the style attribute set by PHP
        const progressPercentage = parseFloat(progressBar.style.width) || 0;
        // Move red circular indicator to the correct position
        progressIndicator.style.left = `calc(${progressPercentage}% - 7px)`;
    }


    // --- Task Detail Modal Logic ---
    const taskDetailModalElement = document.getElementById('taskDetailModal');
    const taskCourseSelect = document.getElementById('taskCourseSelect'); // **** Use new ID ****
    const taskListSection = document.getElementById('taskListSection');
    const taskDetailsSection = document.getElementById('taskDetailsSection');
    const taskList = document.getElementById('taskList');
    const taskPointsList = document.getElementById('taskPointsList');
    const taskSubmissionForm = document.getElementById('taskSubmissionForm');
    const selectedTaskTitle = document.getElementById('selectedTaskTitle');
    const selectedTaskId = document.getElementById('selectedTaskId');
    const fileInput = document.getElementById("fileUpload");
    const fileNameDisplay = document.getElementById("file-name");
    const goToDetailButtons = document.querySelectorAll(".task-card button[data-bs-target='#taskDetailModal']"); // Select button more reliably

    if (taskDetailModalElement && taskCourseSelect && taskListSection && taskDetailsSection && taskList && taskPointsList && taskSubmissionForm && selectedTaskTitle && selectedTaskId && fileInput && fileNameDisplay && goToDetailButtons.length > 0) {
        const taskDetailModal = new bootstrap.Modal(taskDetailModalElement);
        let taskCoursesLoaded = false;

        // Add listener to the button(s) that open the modal
        goToDetailButtons.forEach(button => {
            button.addEventListener("click", function () {
                taskDetailModal.show();
            });
        });


        // Load user's courses when the modal is shown for the first time
        taskDetailModalElement.addEventListener('show.bs.modal', function () {
             if (!taskCoursesLoaded) {
                fetch('/get-user-courses')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            taskCourseSelect.innerHTML = '<option value="">Choose a course...</option>'; // Reset
                            data.courses.forEach(course => {
                                const option = document.createElement('option');
                                option.value = course.id;
                                option.textContent = course.title;
                                taskCourseSelect.appendChild(option);
                            });
                            taskCoursesLoaded = true;
                        } else {
                             console.error("Failed to load courses for task modal.");
                             taskCourseSelect.innerHTML = '<option value="">Error loading courses</option>';
                        }
                    })
                    .catch(error => {
                         console.error("Error fetching courses for task modal:", error);
                         taskCourseSelect.innerHTML = '<option value="">Error loading courses</option>';
                    });
            }
            // Reset modal state when shown
            taskListSection.style.display = 'none';
            taskDetailsSection.style.display = 'none';
            taskList.innerHTML = '<p class="text-muted text-center">Select a course to view tasks.</p>'; // Reset task list
            if(taskCourseSelect.value) { // If a course was previously selected, trigger change to reload
                 taskCourseSelect.dispatchEvent(new Event('change'));
            }
        });

        // Handle course selection in Task Detail Modal
        taskCourseSelect.addEventListener('change', function() {
            const courseId = this.value;
            taskList.innerHTML = '<p class="text-muted text-center">Loading tasks...</p>'; // Loading indicator
            taskListSection.style.display = 'block'; // Show section
            taskDetailsSection.style.display = 'none'; // Hide details

            if (courseId) {
                // Assuming '/get-next-task/{courseId}' ACTUALLY returns ALL tasks for the course.
                // If not, you need a different endpoint like '/get-course-tasks/{courseId}'
                fetch(`/get-next-task/${courseId}`) // MAKE SURE THIS ENDPOINT RETURNS ALL TASKS FOR THE COURSE
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && Array.isArray(data.tasks)) {
                            taskList.innerHTML = ''; // Clear loading/previous tasks
                            if (data.tasks.length === 0) {
                                taskList.innerHTML = '<p class="text-muted text-center">No tasks found for this course.</p>';
                            } else {
                                data.tasks.forEach(task => {
                                    const taskElement = document.createElement('a');
                                    taskElement.href = '#';
                                    // Add null check for status before calling getStatusClass/Badge
                                    const status = task.status ?? 'not_submitted'; // Default status if null
                                    const grade = task.grade ?? null;
                                    taskElement.className = `list-group-item list-group-item-action d-flex justify-content-between align-items-center ${getStatusClass(status)}`;
                                    taskElement.innerHTML = `
                                        <div>
                                            <h6 class="mb-1">${task.title || 'Untitled Task'}</h6>
                                            <p class="mb-1 small text-muted">${StrLimit(task.description || 'No description', 100)}</p>
                                            <small>Due: ${formatDate(task.due_date)}</small>
                                        </div>
                                        <small>${getStatusBadge(status, grade)}</small>
                                    `;
                                    taskElement.addEventListener('click', (e) => {
                                        e.preventDefault();
                                        // Pass the full task object which should include taskPoints
                                        showTaskDetails(task);
                                    });
                                    taskList.appendChild(taskElement);
                                });
                            }
                        } else {
                            taskList.innerHTML = '<p class="text-danger text-center">Error loading tasks.</p>';
                            console.error("Failed to load tasks or invalid format:", data);
                        }
                    })
                    .catch(error => {
                         taskList.innerHTML = '<p class="text-danger text-center">Error loading tasks.</p>';
                         console.error("Error fetching tasks:", error);
                    });
            } else {
                taskListSection.style.display = 'none'; // Hide if no course selected
                taskDetailsSection.style.display = 'none';
            }
        });

        function showTaskDetails(task) {
            if (!task) return;

            selectedTaskTitle.textContent = task.title || 'Task Details';
            selectedTaskId.value = task.id;

            // Populate task points checklist
            taskPointsList.innerHTML = ''; // Clear previous points
            if (task.taskPoints && Array.isArray(task.taskPoints) && task.taskPoints.length > 0) {
                 task.taskPoints.forEach((point, index) => {
                    if (point && point.id) { // Check if point and point.id exist
                        const pointElement = document.createElement('div');
                        pointElement.className = 'form-check mb-2';
                        pointElement.innerHTML = `
                            <input class="form-check-input" type="checkbox" id="point${point.id}" disabled> <!-- Usually disabled for view -->
                            <label class="form-check-label" for="point${point.id}">
                                ${index + 1}. ${point.title || 'Untitled Point'}
                            </label>
                            ${point.notes ? `<p class="text-muted small ms-4 mb-0 fst-italic">${point.notes}</p>` : ''}
                        `;
                        taskPointsList.appendChild(pointElement);
                    }
                 });
            } else {
                 taskPointsList.innerHTML = '<p class="text-muted small">No specific task points listed.</p>';
            }

            // Reset form fields
            taskSubmissionForm.reset();
            fileNameDisplay.textContent = ''; // Clear file name display

            taskDetailsSection.style.display = 'block'; // Show the details section
            taskListSection.style.display = 'none'; // Hide the task list
        }

        function getStatusClass(status) {
            switch(status) {
                case 'reviewed': return 'list-group-item-light border-success'; // More subtle success
                case 'pending': return 'list-group-item-light border-warning'; // More subtle warning
                case 'submitted': return 'list-group-item-light border-warning'; // Same as pending for visual cue
                default: return ''; // Not submitted or unknown
            }
        }

        function getStatusBadge(status, grade) {
            switch(status) {
                case 'reviewed':
                    const gradeText = (grade !== null && grade !== undefined) ? ` (${grade}%)` : '';
                    return `<span class="badge bg-success">Reviewed${gradeText}</span>`;
                case 'pending':
                    return '<span class="badge bg-warning text-dark">Pending Review</span>';
                 case 'submitted': // Added distinct status if your backend uses it
                    return '<span class="badge bg-info text-dark">Submitted</span>';
                default:
                    return '<span class="badge bg-secondary">Not Submitted</span>';
            }
        }

        function formatDate(dateString) {
            if (!dateString) return 'No due date';
            try {
                // Attempt to format nicely, fallback if invalid date
                return new Date(dateString).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
            } catch (e) {
                return dateString; // Return original string if formatting fails
            }
        }

        // Utility to limit string length (since Blade's Str::limit isn't available in JS)
        function StrLimit(text, limit) {
            if (!text) return '';
            return text.length > limit ? text.substring(0, limit) + '...' : text;
        }


        // Handle file input display
        fileInput.addEventListener("change", function () {
            if (fileInput.files.length > 0) {
                const fileNames = Array.from(fileInput.files)
                    .map(file => `${file.name} (${(file.size / 1024).toFixed(1)} KB)`)
                    .join('<br>'); // Use <br> for line breaks if needed, or just join(', ')
                fileNameDisplay.innerHTML = fileNames; // Use innerHTML if using <br>
            } else {
                fileNameDisplay.textContent = "";
            }
        });


        // Handle form submission with SweetAlert
        taskSubmissionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const currentTaskId = selectedTaskId.value;

            if (!currentTaskId) {
                 Swal.fire('Error', 'No task selected.', 'error');
                 return;
            }

            // Optional: Check if files were selected if required
            // if (fileInput.files.length === 0) {
            //     Swal.fire('Missing Files', 'Please select files to upload.', 'warning');
            //     return;
            // }

            // Show loading state
                Swal.fire({
                title: 'Submitting...',
                text: 'Please wait while your task is being submitted.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });


            // Check if task was already submitted (optional, depends on backend logic)
            fetch(`/check-task-submission/${currentTaskId}`) // You need to implement this route
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    Swal.fire({
                        title: 'Task Already Submitted',
                        text: 'You have already submitted this task. Submitting again will replace the previous submission. Continue?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, submit again',
                    cancelButtonText: 'Cancel'
                 }).then((result) => {
                    if (result.isConfirmed) {
                            submitTaskActual(formData, currentTaskId);
                        } else {
                             Swal.close(); // Close loading indicator if cancelled
                        }
                    });
                } else {
                    // If it doesn't exist or check fails, proceed with submission
                    submitTaskActual(formData, currentTaskId);
                }
            })
            .catch(error => {
                 console.warn("Could not check previous submission status, proceeding anyway.", error);
                 submitTaskActual(formData, currentTaskId); // Proceed even if check fails
            });
        });

        function submitTaskActual(formData, taskId) {
             // Show loading state (might be redundant if already shown, but safe)
             Swal.fire({
                title: 'Submitting...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() }
            });

            fetch(`/task/${taskId}/submit`, { // Make sure this route exists and handles POST
                method: 'POST',
                body: formData,
                 headers: {
                    // Important: Let the browser set Content-Type for FormData
                    // 'Content-Type': 'multipart/form-data' is NOT needed here
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '' // Add CSRF token if needed by Laravel
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    taskDetailModal.hide(); // Hide modal first
                    Swal.fire({
                        title: 'Success!',
                        text: data.message || 'Your task has been submitted successfully!',
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        window.location.reload(); // Reload the page to update the UI and prevent freezing
                        // taskCourseSelect.dispatchEvent(new Event('change')); // Optionally keep this if you want to reload tasks only
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'An error occurred while submitting the task.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Error submitting task:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'A network or server error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
        }

    } else {
        console.warn("Task Detail modal elements not fully found. Functionality might be limited.");
    }

    // --- Mentor Bot Modal Logic ---
    const askButton = document.getElementById("askMentorBot");
    const mentorModal = new bootstrap.Modal(document.getElementById("mentorBotModal"));
    const chatInput = document.querySelector(".chat-input input");
    const sendButton = document.querySelector(".chat-input .btn-primary");
    const chatMessages = document.querySelector(".chat-messages");
    const categoryButtons = document.querySelectorAll(".chat-categories .btn");
    let currentCategory = 'ui/ux'; // Default category

    // Open modal when Ask button is clicked
    askButton?.addEventListener("click", () => {
        mentorModal.show();
        // Add initial welcome message based on current category
        addBotMessage(getCategoryWelcomeMessage(currentCategory));
    });

    // Handle category switching
    categoryButtons.forEach(button => {
        button.addEventListener("click", () => {
            categoryButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");
            currentCategory = button.textContent.toLowerCase();
            
            // Clear chat messages and show new category welcome message
            chatMessages.innerHTML = '';
            addBotMessage(getCategoryWelcomeMessage(currentCategory));
        });
    });

    // Function to get welcome message based on category
    function getCategoryWelcomeMessage(category) {
        switch(category) {
            case 'ui/ux':
                return "Start asking about UI/UX design! I can help you with design principles, user experience, and interface design.";
            case 'development':
                return "Start asking about Development! I can help you with programming, coding practices, and software development.";
            case 'marketing':
                return "Start asking about Marketing! I can help you with digital marketing, strategies, and campaign management.";
            default:
                return "How can I help you today?";
        }
    }

    // Function to get category description
    function getCategoryDescription(category) {
        switch(category) {
            case 'ui/ux':
                return "UI/UX design focuses on creating intuitive and engaging user interfaces and experiences. It combines visual design, user research, and interaction design to create products that are both functional and enjoyable to use.";
            case 'development':
                return "Development involves creating software applications and systems. It includes programming, testing, debugging, and maintaining code. Developers use various programming languages and frameworks to build everything from websites to complex applications.";
            case 'marketing':
                return "Marketing is the process of promoting and selling products or services. It includes market research, advertising, public relations, and digital marketing strategies to reach and engage target audiences.";
            default:
                return "I'm not sure about that topic. Please select a category first.";
        }
    }

    // Function to add bot message
    function addBotMessage(message) {
        const messageDiv = document.createElement("div");
        messageDiv.className = "message bot-message mb-4";
        messageDiv.innerHTML = `
            <div class="message-content bg-light p-3 rounded-3 d-inline-block">
                ${message}
            </div>
            <div class="message-time text-muted small mt-1">
                ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Send message function
    function sendMessage() {
        const message = chatInput.value.trim().toLowerCase();
        if (!message) return;

        // Create user message element
        const messageDiv = document.createElement("div");
        messageDiv.className = "message user-message text-end mb-4";
        messageDiv.innerHTML = `
            <div class="message-content bg-primary text-white p-3 rounded-3 d-inline-block">
                ${message}
            </div>
            <div class="message-time text-muted small mt-1">
                ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
            </div>
        `;

        // Add user message to chat
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        chatInput.value = "";

        // Check if user asked for category description
        if (message.includes("tell me about")) {
            setTimeout(() => {
                addBotMessage(getCategoryDescription(currentCategory));
            }, 500);
        } else {
            // Add a generic response for other questions
            setTimeout(() => {
                addBotMessage("I'm here to help! Feel free to ask specific questions about " + currentCategory + ".");
            }, 500);
        }
    }

    // Send button click handler
    sendButton?.addEventListener("click", sendMessage);

    // Enter key handler
    chatInput?.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            sendMessage();
        }
    });

    // Function to show loading state
    function showLoading() {
        const card = document.querySelector('.stats-card');
        if (card) card.classList.add('loading');

        const elements = ['tasksPercentage', 'quizzesPercentage', 'viewingPercentage', 'overallProgress'];
        elements.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.innerHTML = '<span class="loading-spinner"></span><span class="loading-text">Loading...</span>';
            }
        });
    }

    // Function to hide loading state
    function hideLoading() {
        const card = document.querySelector('.stats-card');
        if (card) card.classList.remove('loading');
    }

    // Function to load courses into dropdown
    function loadCourses() {
        showLoading();
        fetch('/get-user-courses')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.courses.length > 0) {
                    const courseSelect = document.getElementById('courseSelect');
                    courseSelect.innerHTML = '<option value="">All Courses</option>';
                    data.courses.forEach((course, index) => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.title;
                        courseSelect.appendChild(option);
                        if (index === 0) {
                            courseSelect.value = course.id;
                        }
                    });
                    updateDashboardStats();
                } else {
                    showNoData();
                }
            })
            .catch(error => {
                console.error("Error loading courses:", error);
                showNoData();
            });
    }

    // Function to update dashboard statistics
    function updateDashboardStats() {
        const courseSelect = document.getElementById('courseSelect');
        const selectedCourseId = courseSelect.value;

        showLoading();

        if (!selectedCourseId) {
            // Show statistics for all courses
            fetch('/get-user-courses')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.courses.length > 0) {
                        let totalTasksGrade = 0;
                        let totalQuizzesMarks = 0;
                        let totalLessonsCompletion = 0;
                        let coursesCount = data.courses.length;
                        let processedCourses = 0;

                        // Create an array to store all promises
                        const promises = [];

                        // Use Promise.all to fetch all statistics in parallel
                        data.courses.forEach(course => {
                            const courseId = course.id;
                            
                            promises.push(
                                Promise.all([
                                    fetch(`/course/${courseId}/task-statistics`).then(r => r.json()),
                                    fetch(`/course/${courseId}/quiz-statistics`).then(r => r.json()),
                                    fetch(`/course/${courseId}/lesson-statistics`).then(r => r.json())
                                ]).then(([taskData, quizData, lessonData]) => {
                                    totalTasksGrade += taskData.average_grade ?? 0;
                                    totalQuizzesMarks += quizData.average_marks ?? 0;
                                    totalLessonsCompletion += lessonData.completion_percentage ?? 0;
                                    processedCourses++;
                                })
                            );
                        });

                        // Wait for all promises to complete
                        Promise.all(promises)
                            .then(() => {
                                updateStatsUI(
                                    Math.round(totalTasksGrade / coursesCount),
                                    Math.round(totalQuizzesMarks / coursesCount),
                                    Math.round(totalLessonsCompletion / coursesCount)
                                );
                                hideLoading();
                            })
                            .catch(error => {
                                console.error("Error calculating all courses statistics:", error);
                                showNoData();
                            });
                    } else {
                        showNoData();
                    }
                })
                .catch(error => {
                    showNoData();
                });
        } else {
            // Show statistics for selected course
            Promise.all([
                fetch(`/course/${selectedCourseId}/task-statistics`).then(r => r.json()),
                fetch(`/course/${selectedCourseId}/quiz-statistics`).then(r => r.json()),
                fetch(`/course/${selectedCourseId}/lesson-statistics`).then(r => r.json())
            ]).then(([taskData, quizData, lessonData]) => {
                updateStatsUI(
                    taskData.average_grade ?? 0,
                    quizData.average_marks ?? 0,
                    lessonData.completion_percentage ?? 0
                );
                hideLoading();
            }).catch(error => {
                console.error("Error fetching course statistics:", error);
                showNoData();
            });
        }
    }

    // Function to update UI with statistics
    function updateStatsUI(tasksGrade, quizzesMarks, lessonsCompletion) {
        const tasksEl = document.getElementById('tasksPercentage');
        const quizzesEl = document.getElementById('quizzesPercentage');
        const viewingEl = document.getElementById('viewingPercentage');
        const overallEl = document.getElementById('overallProgress');

        if (tasksEl) tasksEl.textContent = `${tasksGrade}%`;
        if (quizzesEl) quizzesEl.textContent = `${quizzesMarks}%`;
        if (viewingEl) viewingEl.textContent = `${lessonsCompletion}%`;
        if (overallEl) {
            const overallProgress = Math.round((tasksGrade + quizzesMarks + lessonsCompletion) / 3);
            overallEl.textContent = `${overallProgress}%`;
            // Update the circular progress bar with animation
            const progressBar = document.querySelector('.progress-bar');
            if (progressBar) {
                const offset = 282.6 - (282.6 * overallProgress / 100);
                progressBar.style.strokeDashoffset = offset;
            }
        }
    }

    // Function to show error state
    function showError() {
        const elements = ['tasksPercentage', 'quizzesPercentage', 'viewingPercentage', 'overallProgress'];
        elements.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = 'Error';
        });
        hideLoading();
    }

    // Add event listener for course selection
    const courseSelect = document.getElementById('courseSelect');
    if (courseSelect) {
        courseSelect.addEventListener('change', updateDashboardStats);
    }

    // Load courses when page loads
    loadCourses();

    function showNoData() {
        const elements = ['tasksPercentage', 'quizzesPercentage', 'viewingPercentage', 'overallProgress'];
        elements.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = 'No data';
        });
        hideLoading();
        // Reset the circular progress bar
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.strokeDashoffset = 282.6;
        }
    }
});
</script>

@endsection