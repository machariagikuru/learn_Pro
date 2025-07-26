@extends('layouts.head')

@section('title', 'LearnPro | ' . $course->title)

@section('content')
<style>
    .btn-success {
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(40, 167, 69, 0.3);
    }
    .complete-course-btn {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        padding: 10px 20px;
        font-weight: bold;
    }
    .complete-course-btn:hover {
        background: linear-gradient(45deg, #20c997, #28a745);
    }

    /* Mentor Bot Button Styles */
    .mentor-bot-button {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        width: auto;
        height: auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
        font-size: 1rem;
        text-decoration: none;
        cursor: pointer;
        margin-top: 20px;
        justify-content: center;
    }

    .mentor-bot-button:hover {
        transform: none;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        background: #0056b3;
        color: white;
    }

    .mentor-bot-button i {
        font-size: 1.1em;
    }

    /* Mentor Bot Modal Styles */
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

    .course-completed-badge {
        background: linear-gradient(45deg, #ffd700, #ffa500);
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
</style>

    <!-- Course Content Section -->
    <div class="container">
        <h2 class="text-primary">{{ $course->title }}</h2>

        <div class="row mt-4">
            <!-- Video & Course Content -->
            <div class="col-md-8 mb-3">
                @if ($videoLesson)
                    <!-- Video Lesson Section -->
                    <h5>{{ $videoLesson->description }}</h5>
                    <div class="video-container my-3">
                        @if (isset($videoLesson->video) || isset($videoLesson->video_url))
                            @if (isset($videoLesson->video))
                                <video width="800" height="495" controls>
                                    <source src="{{ asset($videoLesson->video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @elseif(isset($videoLesson->video_url) &&
                                    (str_contains($videoLesson->video_url, 'youtube.com') || str_contains($videoLesson->video_url, 'youtu.be')))
                                <iframe width="800" height="495" src="{{ $videoLesson->converted_video_url }}"
                                    title="{{ $videoLesson->title }}" frameborder="0" allowfullscreen></iframe>
                            @else
                                <video width="800" height="495" controls>
                                    <source src="{{ asset($videoLesson->video_url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        @else
                            <p class="text-muted">Content uploading soon...</p>
                        @endif
                    </div>
                    <p>{{ $videoLesson->long_description ?? 'No description available.' }}</p>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-3">
                        @if ($prevLesson)
                            <a href="{{ route('course.content', ['id' => $course->id, 'lesson' => $prevLesson->id]) }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Previous Lesson
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-arrow-left me-2"></i>Previous Lesson
                            </button>
                        @endif

                        @if ($nextLesson)
                            <a href="{{ route('course.content', ['id' => $course->id, 'lesson' => $nextLesson->id]) }}"
                                class="btn btn-primary" onclick="markAsWatched({{ $videoLesson->id }})">
                                Next Lesson<i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        @else
                            @php
                                $isCourseCompleted = $user->completedCourses->contains($course->id);
                            @endphp
                            @if (!$isCourseCompleted)
                                <button class="btn btn-success complete-course-btn" 
                                        onclick="completeCourse({{ $videoLesson->id }}, {{ $course->id }})">
                                    <i class="fas fa-check-circle me-2"></i>Complete Course
                                </button>
                            @else
                                <div class="course-completed-badge">
                                    <i class="fas fa-trophy me-2"></i>Course Completed
                                    <button class="btn btn-sm btn-light ms-3" onclick="downloadCertificate({{ $course->id }})">
                                        <i class="fas fa-download me-1"></i>Download Certificate
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            <!-- Lessons Sidebar -->
            <div class="col-md-4">
                @if ($course->chapters->isNotEmpty())
                    @foreach ($course->chapters as $chapter)
                        <div class="accordion mb-4" id="accordionChapters">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $chapter->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $chapter->id }}" aria-expanded="false"
                                        aria-controls="collapse{{ $chapter->id }}">
                                        {{ $chapter->title }} 
                                    </button>
                                </h2>
                                <div id="collapse{{ $chapter->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $chapter->id }}" data-bs-parent="#accordionChapters">
                                    <div class="accordion-body">
                                        {{-- عرض الدروس --}}
                                        <h6 class="mt-3 mb-2 text-muted">Lessons</h6>
                                        @if ($chapter->lessons->isNotEmpty())
                                            <ul class="list-group mb-3">
                                                @foreach ($chapter->lessons as $lesson)
                                                    @php
                                                        // Assume you pass the current authenticated user to your view as $user.
                                                        // Find if this user has a pivot record for this lesson.
                                                        $userLesson = $user->lessons->firstWhere('id', $lesson->id);
                                                        $isWatched = $userLesson ? $userLesson->pivot->watched : false;
                                                    @endphp
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center {{ isset($videoLesson) && $videoLesson->id === $lesson->id ? 'bg-info' : '' }}">
                                                        <a href="{{ route('course.content', ['id' => $course->id, 'lesson' => $lesson->id]) }}"
                                                            style="text-decoration: none; flex-grow: 1;">
                                                            <h6
                                                                class="{{ isset($videoLesson) && $videoLesson->id === $lesson->id ? 'text-dark' : '' }}">
                                                                {{ $lesson->title }}</h6>
                                                            <p
                                                                class="mb-0 {{ isset($videoLesson) && $videoLesson->id === $lesson->id ? 'text-dark' : 'text-muted' }}">
                                                                {{ \Illuminate\Support\Str::limit($lesson->description, 50, '...') }}
                                                            </p>
                                                        </a>
                                                        @if ($isWatched)
                                                            <span class="badge bg-success"><i
                                                                    class="fa-solid fa-check"></i></span>
                                                        @else
                                                            <span class="badge bg-primary">▶</span>
                                                        @endif
                                                    </li>
                                                @endforeach

                                            </ul>
                                        @else
                                            <p class="text-muted">Content uploading soon...</p>
                                        @endif
                                        <h6 class="mt-3 mb-2 text-muted">Quizzes</h6>
                                        {{-- عرض الكويزات --}}
                                        @if ($chapter->quizzes->isNotEmpty())
                                            <ul class="list-group">
                                                @foreach ($chapter->quizzes as $quiz)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="{{ route('quiz.show', ['quiz' => $quiz->id]) }}"
                                                            style="text-decoration: none; flex-grow: 1;">
                                                            <h6>Go To : {{ $quiz->title }}</h6>
                                                            <p class="mb-0 text-muted">Time Limit: {{ $quiz->time_limit }}
                                                                minutes</p>
                                                        </a>
                                                        <span class="badge bg-warning"><i
                                                                class="fa-solid fa-question"></i></span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        {{-- Tasks --}}
                                        @if ($chapter->tasks->isNotEmpty())
                                            <h6 class="mt-3 mb-2 text-muted">Tasks</h6>
                                            <ul class="list-group mb-3">
                                                @foreach ($chapter->tasks as $task)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="{{ route('task.show', ['task' => $task->id]) }}" class="flex-grow-1 text-decoration-none">
                                                            <h6>{{ $task->title }}</h6>
                                                            <p class="mb-0 text-muted">{{ \Illuminate\Support\Str::limit($task->description, 50, '...') }}</p>
                                                        </a>
                                                        <span class="badge bg-info ms-2" title="View Task"><i class="fa-solid fa-list-check"></i></span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No chapters available yet. Content uploading soon...</p>
                @endif

                <!-- Mentor Bot Button -->
                <button class="mentor-bot-button" data-bs-toggle="modal" data-bs-target="#mentorBotModal">
                    <i class="fas fa-robot"></i>
                    Ask Mentor Bot
                </button>
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
                            <img src="{{ asset('assets/images/robot.png') }}" alt="Mentor Bot" class="rounded-circle" width="30" height="30">
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

@endsection

<script>
    function markAsWatched(lessonId) {
        fetch('/mark-lesson-watched/' + lessonId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Lesson marked as watched");
                } else {
                    console.error("Could not mark lesson as watched.");
                }
            })
            .catch(error => console.error("Error:", error));
    }

    function completeCourse(lessonId, courseId) {
        // First mark the last lesson as watched
        fetch('/mark-lesson-watched/' + lessonId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                Swal.fire({
                    title: 'Error',
                    text: 'Could not mark lesson as watched. Please try again.',
                    icon: 'error'
                });
                return;
            }

            // Then check if all lessons are watched
            fetch('/check-all-lessons-watched/' + courseId, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.allWatched) {
                    Swal.fire({
                        title: 'Cannot Complete Course',
                        text: 'Please watch all lessons before completing the course.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // If all lessons are watched, show completion message with options
                Swal.fire({
                    title: 'Congratulations! 🎉',
                    text: 'You have completed all lessons in this course!',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Download Certificate',
                    cancelButtonText: 'View Dashboard',
                    showDenyButton: true,
                    denyButtonText: 'Stay Here',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Download certificate
                        window.location.href = '/generate-certificate/' + courseId;
                    } else if (result.isDenied) {
                        // Stay on the page and show completion badge
                        const buttonContainer = document.querySelector('.complete-course-btn').parentElement;
                        buttonContainer.innerHTML = `
                            <div class="course-completed-badge">
                                <i class="fas fa-trophy me-2"></i>Course Completed
                                <button class="btn btn-sm btn-light ms-3" onclick="downloadCertificate(${courseId})">
                                    <i class="fas fa-download me-1"></i>Download Certificate
                                </button>
                            </div>
                        `;
                    } else {
                        // Go to dashboard
                        window.location.href = '/dashboard';
                    }
                });
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while checking lesson completion status.',
                    icon: 'error'
                });
            });
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while marking lesson as watched.',
                icon: 'error'
            });
        });
    }

    function downloadCertificate(courseId) {
        window.location.href = '/generate-certificate/' + courseId;
    }

    // Mentor Bot Modal Logic
    document.addEventListener("DOMContentLoaded", function() {
        // --- Mentor Bot Modal Logic ---
        const mentorModal = new bootstrap.Modal(document.getElementById("mentorBotModal"));
        const chatInput = document.querySelector(".chat-input input");
        const sendButton = document.querySelector(".chat-input .btn-primary");
        const chatMessages = document.querySelector(".chat-messages");
        const categoryButtons = document.querySelectorAll(".chat-categories .btn");
        let currentCategory = 'ui/ux'; // Default category

        // Add initial welcome message when modal is shown
        document.getElementById("mentorBotModal").addEventListener("show.bs.modal", function() {
            chatMessages.innerHTML = ''; // Clear previous messages
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
    });
</script>
