@php
    use Carbon\Carbon;
    // Set default passing score if not explicitly set on the quiz
    $passingScore = $quiz->passing_score ?? 50;
@endphp

@extends('layouts.head') {{-- Assuming 'layouts.head' includes necessary CSS/JS headers --}}

@section('title', 'Quiz: ' . $quiz->title)

@section('content')
<div class="container mt-5 mb-5"> {{-- Added margin top/bottom for spacing --}}
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12"> {{-- Adjust column width as needed --}}

            {{-- Quiz Header --}}
            <div class="text-center mb-4">
                <h2 class="text-primary mb-1">{{ $quiz->title }}</h2>
                @if($quiz->chapter && $quiz->chapter->title)
                    <p class="text-muted mb-0">
                        Part of Chapter:
                        <span class="text-info">{{ $quiz->chapter->title }}</span>
                         @if($quiz->chapter->course && $quiz->chapter->course->title)
                            (Course: {{ $quiz->chapter->course->title }})
                         @endif
                    </p>
                @endif
            </div>

            <!-- Quiz Container -->
            <div class="quiz-container">

                {{-- CONDITIONAL DISPLAY: Show Previous Result or Attempt Card --}}
                @if($hasAttempted && isset($previousAttempt))
                    {{-- ========= Show Previous Result Card ========= --}}
                    <div id="previousResultCard" class="card quiz-card p-4 mt-4 shadow-lg border border-warning">
                        <h4 class="text-center text-warning mb-3">
                            <i class="fa-solid fa-check-double me-2"></i>You Have Already Attempted This Quiz
                        </h4>
                        <p class="text-center mb-4">Here is your most recent result:</p>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center align-middle">
                                <tbody>
                                    <tr>
                                        <th class="bg-light w-50"><i class="fa-regular fa-calendar-check me-1"></i> Attempted On</th>
                                        <td>{{ $previousAttempt->created_at ? Carbon::parse($previousAttempt->created_at)->format('l, d F Y - H:i') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light"><i class="fa-solid fa-bullseye me-1"></i> Score Percentage</th>
                                        <td class="fw-bold {{ $previousAttempt->percentage >= $passingScore ? 'text-success' : 'text-danger' }}">
                                            {{ $previousAttempt->percentage ?? 'N/A' }}%
                                            @if($previousAttempt->percentage !== null)
                                                @if($previousAttempt->percentage >= $passingScore)
                                                    <span class="badge bg-success ms-2">Passed</span>
                                                @else
                                                    <span class="badge bg-danger ms-2">Failed</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                     <tr>
                                        <th class="bg-light"><i class="fa-regular fa-circle-check me-1"></i> Correct / Total</th>
                                        <td>
                                            <span class="badge bg-success me-1">{{ $previousAttempt->correct_answers ?? 'N/A' }}</span> /
                                            <span class="badge bg-secondary ms-1">{{ $previousAttempt->total_questions ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                     <tr>
                                        <th class="bg-light"><i class="fa-regular fa-clock me-1"></i> Time Taken</th>
                                        <td>
                                            @if($previousAttempt->time_taken !== null)
                                                {{ floor($previousAttempt->time_taken / 60) }}m {{ $previousAttempt->time_taken % 60 }}s
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Action Buttons for Previous Result --}}
                        <div class="text-center">
                            <button class="btn btn-danger px-4 me-2" id="retakeQuizBtn">
                                <i class="fa-solid fa-arrow-rotate-right me-1"></i> Retake Quiz
                            </button>
                             @if ($quiz->chapter && $quiz->chapter->course)
                            <a href="{{ route('course.content', $quiz->chapter->course->id) }}" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i> Back to Course
                            </a>
                            @endif
                        </div>
                        <p class="text-muted text-center mt-3 small fst-italic">
                            <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i>
                            Retaking the quiz will delete this previous result and record a new one.
                        </p>
                    </div>

                @else
                    {{-- ========= Show Initial Quiz Card (if not attempted) ========= --}}
                    <div id="quizCard" class="card quiz-card p-4 mt-4 shadow-lg border border-primary">
                         <h4 class="text-center text-primary mb-3">Quiz Details</h4>
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="mb-3 mb-md-0 me-3"> {{-- Added right margin --}}
                                <p class="text-muted mb-1">
                                    <i class="fa-regular fa-calendar-alt me-1"></i>
                                    Created: {{ $quiz->created_at ? Carbon::parse($quiz->created_at)->format('d F Y') : 'N/A' }}
                                </p>
                                @if($quiz->chapter?->course?->user)
                                <p class="text-muted mb-1">
                                    <i class="fa-solid fa-user-tie me-1"></i>
                                    Instructor: Mr./Ms. {{ $quiz->chapter->course->user->first_name ?? 'N/A' }}
                                </p>
                                @endif
                                <p class="text-muted mb-1">
                                    <i class="fa-solid fa-bullseye me-1"></i> Passing Score:
                                    <span class="fw-bold">{{ $passingScore }}%</span>
                                </p>
                                 <p class="text-muted mb-0">
                                     <i class="fa-regular fa-clock me-1"></i> Time Limit:
                                     <span class="fw-bold">{{ $quiz->time_limit ?? 'N/A' }} minutes</span>
                                 </p>
                            </div>
                            {{-- Attempt Button --}}
                            <button class="btn btn-primary px-4 attempt-btn flex-shrink-0" id="attemptQuiz"> {{-- Added flex-shrink-0 --}}
                               <i class="fa-solid fa-play me-1"></i> Attempt Quiz Now
                            </button>
                        </div>
                    </div>
                @endif
                {{-- End Conditional Display --}}


                {{-- ========= Timer (Hidden by Default) ========= --}}
                <div class="text-end me-md-4 mt-3" id="quiz-timer-container" style="display: none;">
                     <span class="badge bg-primary fs-5 border border-3 border-secondary shadow-sm" id="quiz-timer">
                         <i class="fa-regular fa-hourglass-half me-1"></i>
                         {{ floor($quiz->time_limit) }}:00
                     </span>
                </div>

                {{-- ========= Quiz Questions Area (Hidden by Default) ========= --}}
                <div class="mt-3" id="quiz-questions" style="display: none;">

                     {{-- Container for Dynamically Added Completion Message --}}
                    <div id="quizCompletionMessage" class="alert alert-success text-center mt-3" style="display: none;"></div>

                    {{-- Container for Dynamically Added Quiz Report --}}
                    <div id="quizReportContainer" class="mt-4" style="display: none;"></div>

                    {{-- Container for Dynamically Added Post-Quiz Navigation --}}
                    <div id="quizNavigationButtons" class="mt-4 text-center" style="display: none;"></div>

                    {{-- Questions will be displayed here --}}
                    <div id="questionsContainer" class="mt-3"> {{-- Added margin-top --}}
                      {{-- Loop through questions and generate cards --}}
                      @forelse($quiz->questions as $index => $question)
                        <div class="card mb-3 question-card shadow-sm" data-index="{{ $index }}" style="display: none;">
                            {{-- Card header background updates after submission --}}
                            <div class="card-header bg-primary text-white">
                                <span class="fw-bold">Question {{ $index + 1 }}</span>
                                <p class="mb-0 mt-1">{{ $question->question_text }}</p> {{-- Question text below --}}
                            </div>
                            <div class="card-body">
                                <form>
                                    {{-- Render options dynamically if they exist --}}
                                    @if($question->option_a)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="q{{ $question->id }}" id="q{{ $question->id }}_a" value="option_a">
                                        <label class="form-check-label" for="q{{ $question->id }}_a">{{ $question->option_a }}</label>
                                    </div>
                                    @endif
                                    @if($question->option_b)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="q{{ $question->id }}" id="q{{ $question->id }}_b" value="option_b">
                                        <label class="form-check-label" for="q{{ $question->id }}_b">{{ $question->option_b }}</label>
                                    </div>
                                    @endif
                                    @if($question->option_c)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="q{{ $question->id }}" id="q{{ $question->id }}_c" value="option_c">
                                        <label class="form-check-label" for="q{{ $question->id }}_c">{{ $question->option_c }}</label>
                                    </div>
                                    @endif
                                    @if($question->option_d)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="q{{ $question->id }}" id="q{{ $question->id }}_d" value="option_d">
                                        <label class="form-check-label" for="q{{ $question->id }}_d">{{ $question->option_d }}</label>
                                    </div>
                                    @endif
                                </form>
                                {{-- Feedback appears here after submission --}}
                                <div class="question-feedback mt-3 border-top pt-2" style="display: none;"></div> {{-- Hidden initially --}}
                            </div>
                        </div>
                      @empty
                        <div class="alert alert-warning text-center">
                            <i class="fa-solid fa-circle-exclamation me-2"></i> No questions have been added to this quiz yet.
                        </div>
                      @endforelse
                    </div>

                    {{-- Pagination Navigation Buttons --}}
                     @if ($quiz->questions->count() > 0) {{-- Only show pagination if there are questions --}}
                    <div class="text-center mb-3 mt-4 pt-3 border-top" id="paginationContainer" style="display: none;">
                        <button class="btn btn-secondary me-2" id="prevButton" style="display: none;">
                            <i class="fa-solid fa-arrow-left me-1"></i> Previous
                        </button>
                        <button class="btn btn-primary me-2" id="nextButton" style="display: none;">
                            Next <i class="fa-solid fa-arrow-right ms-1"></i>
                        </button>
                        {{-- Finish button appears on the last page --}}
                        <button class="btn btn-success" id="finishQuiz" style="display: none;">
                            <i class="fa-solid fa-flag-checkered me-1"></i> Finish & Submit Quiz
                        </button>
                    </div>
                    @endif

                </div> {{-- End #quiz-questions --}}

            </div> {{-- End .quiz-container --}}
        </div> {{-- End .col --}}
    </div> {{-- End .row --}}
</div> {{-- End .container --}}

{{-- Include SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Ensure Bootstrap JS is included (likely in layouts.head) --}}
{{-- <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script> --}}

{{-- Main Quiz JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() { // Ensure DOM is ready

    // --- Configuration & State Variables ---
    const quizId = {{ $quiz->id }};
    const totalTimeSeconds = {{ $quiz->time_limit * 60 }};
    const passingScore = {{ $passingScore }};
    const questionsPerPage = 5; // Adjust as needed
    const deleteResultRoute = '{{ route("quizresults.deletePrevious", $quiz->id) }}';
    const storeResultRoute = '{{ route("quizresults.store") }}';
    const csrfToken = "{{ csrf_token() }}";
    @if ($quiz->chapter && $quiz->chapter->course)
    const courseContentRoute = '{{ route("course.content", $quiz->chapter->course->id) }}';
    @else
    const courseContentRoute = '#'; // Fallback if no course context
    @endif

    let timeLeft = totalTimeSeconds;
    let timerInterval;
    let quizFinished = false;
    let warningPlayed = false;
    let criticalPlayed = false;
    let currentPage = 0;

    // Build the correct answers mapping from Blade/PHP data
    const correctAnswers = @json(
        $quiz->questions->mapWithKeys(function($question) {
            return ['q' . $question->id => $question->correct_option];
        })
    );
    const allQuestionCards = document.querySelectorAll('.question-card');
    const totalQuestions = allQuestionCards.length;
    const totalPages = Math.ceil(totalQuestions / questionsPerPage);

    // --- Audio Setup ---
    const warningSound = new Audio('{{ asset('assets/sounds/warning.mp3') }}');
    const criticalSound = new Audio('{{ asset('assets/sounds/critical.mp3') }}');
    const timeUpSound = new Audio('{{ asset('assets/sounds/timeup.mp3') }}');
    warningSound.preload = 'auto';
    criticalSound.preload = 'auto';
    timeUpSound.preload = 'auto';

    // --- DOM Element References ---
    const quizCard = document.getElementById('quizCard');
    const previousResultCard = document.getElementById('previousResultCard');
    const timerContainer = document.getElementById('quiz-timer-container');
    const timerElement = document.getElementById('quiz-timer');
    const quizQuestionsContainer = document.getElementById('quiz-questions');
    const questionsDisplayArea = document.getElementById('questionsContainer'); // Where question cards live
    const paginationContainer = document.getElementById('paginationContainer');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    const finishButton = document.getElementById('finishQuiz');
    const attemptButton = document.getElementById('attemptQuiz'); // Button on initial card
    const retakeButton = document.getElementById('retakeQuizBtn'); // Button on previous result card
    const completionMessageDiv = document.getElementById('quizCompletionMessage');
    const reportContainerDiv = document.getElementById('quizReportContainer');
    const navButtonsDiv = document.getElementById('quizNavigationButtons');

    // --- Core Functions ---

    /**
     * Shows the questions for the current page and updates pagination buttons.
     * @param {number} page - The zero-based page index to display.
     */
    function showPage(page) {
        currentPage = page; // Update global current page

        allQuestionCards.forEach((card, index) => {
            const startIndex = page * questionsPerPage;
            const endIndex = startIndex + questionsPerPage;
            card.style.display = (index >= startIndex && index < endIndex) ? 'block' : 'none';
        });

        // Update pagination button visibility
        if (prevButton) prevButton.style.display = (page > 0) ? 'inline-block' : 'none';

        if (nextButton) {
            nextButton.style.display = (page < totalPages - 1) ? 'inline-block' : 'none';
        }

        if (finishButton) {
            // Show finish button only on the last page (or if only one page exists)
             finishButton.style.display = (page === totalPages - 1 || totalPages <= 1 ) && totalQuestions > 0 ? 'inline-block' : 'none';
        }
    }

    /**
     * Updates the timer display, changes styles based on time left, and plays sounds.
     */
    function updateTimer() {
        if (quizFinished || timeLeft < 0) { // Added check for timeLeft < 0
            clearInterval(timerInterval);
            return;
        }

        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.innerHTML = `<i class="fa-regular fa-hourglass-half me-1"></i> ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        // Update timer styles and play warning sounds
        timerElement.classList.remove("bg-primary", "bg-warning", "text-dark", "bg-danger", "text-white", "bg-success");
        if (timeLeft <= 30 && timeLeft > 0) {
            timerElement.classList.add("bg-danger", "text-white");
            if (!criticalPlayed) {
                playSound(criticalSound);
                criticalPlayed = true;
            }
        } else if (timeLeft <= 60 && timeLeft > 30) {
            timerElement.classList.add("bg-warning", "text-dark");
            if (!warningPlayed) {
                playSound(warningSound);
                warningPlayed = true;
            }
        } else if (timeLeft > 60) {
             timerElement.classList.add("bg-primary", "text-white");
        }

        // Handle time up
        if (timeLeft === 0) {
            clearInterval(timerInterval);
            stopAllSounds();
            playSound(timeUpSound);
            timerElement.classList.remove("bg-primary", "bg-warning", "text-dark");
            timerElement.classList.add("bg-danger", "text-white");
            timerElement.innerHTML = `<i class="fa-solid fa-clock me-1"></i> 0:00`;
            Swal.fire({
                title: 'Time is Up!',
                text: 'Your quiz will be submitted automatically.',
                icon: 'warning',
                timer: 3500, // Slightly longer timer
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didClose: () => { // Use didClose to ensure it runs after modal closes
                    if (!quizFinished) {
                        submitQuiz();
                    }
                }
            });
        }

        timeLeft--; // Decrement time left
    }

    /**
     * Safely plays an audio element, handling potential errors.
     * @param {HTMLAudioElement} sound - The audio element to play.
     */
    function playSound(sound) {
        sound.play().catch(error => console.error("Audio playback failed:", error));
    }

    /**
     * Stops all currently playing warning/alert sounds and resets their time.
     */
    function stopAllSounds() {
        [warningSound, criticalSound, timeUpSound].forEach(sound => {
            if (!sound.paused) {
                sound.pause();
                sound.currentTime = 0;
            }
        });
    }


     /**
     * Hides initial cards, shows quiz interface, starts timer.
     */
     function startQuizInterface() {
        // Hide initial cards
        if (quizCard) quizCard.style.display = 'none';
        if (previousResultCard) previousResultCard.style.display = 'none';

        // Reset state for a fresh attempt
        timeLeft = totalTimeSeconds;
        quizFinished = false;
        warningPlayed = false;
        criticalPlayed = false;
        currentPage = 0; // Start from the first page

         // Reset question states (styles, feedback, disabled inputs)
        allQuestionCards.forEach(card => {
            const header = card.querySelector('.card-header');
            const feedback = card.querySelector('.question-feedback');
            const inputs = card.querySelectorAll('.form-check-input');
            const labels = card.querySelectorAll('.form-check-label');

            header.classList.remove('bg-success', 'bg-danger', 'bg-secondary');
            header.classList.add('bg-primary'); // Reset to default
            if (feedback) feedback.innerHTML = '';
            if (feedback) feedback.style.display = 'none'; // Hide feedback area
            inputs.forEach(input => {
                input.checked = false;
                input.disabled = false;
            });
            labels.forEach(label => {
                label.classList.remove('text-success', 'fw-bold', 'text-danger', 'text-decoration-line-through', 'text-warning');
            });
        });

        // Clear previous results/messages if they exist from a prior run on the same page load
        if(completionMessageDiv) completionMessageDiv.style.display = 'none';
        if(reportContainerDiv) reportContainerDiv.style.display = 'none';
        if(navButtonsDiv) navButtonsDiv.style.display = 'none';


        // Show quiz elements
        if (timerContainer) timerContainer.style.display = 'block';
        if (quizQuestionsContainer) quizQuestionsContainer.style.display = 'block';
        if (paginationContainer) paginationContainer.style.display = (totalQuestions > 0) ? 'block' : 'none'; // Show pagination only if questions exist

        // Display the first page of questions (or handle no questions case)
         if (totalQuestions > 0) {
             showPage(0);
         } else {
             // Optionally display a message if no questions, though the Blade loop handles this initially
             if (paginationContainer) paginationContainer.style.display = 'none';
         }


        // Clear existing timer and start a new one
        if (timerInterval) clearInterval(timerInterval);
        if (totalQuestions > 0) { // Only start timer if there are questions
             timerInterval = setInterval(updateTimer, 1000);
             updateTimer(); // Initial call to display time immediately
        } else {
            // If no questions, maybe disable finish button or show a specific message
            if (timerElement) timerElement.innerHTML = '<i class="fa-solid fa-ban me-1"></i> No Questions';
            if (finishButton) finishButton.disabled = true;
        }
    }


    /**
     * Handles the confirmation dialog before starting the quiz.
     */
    function confirmAndStartQuiz() {
        Swal.fire({
            title: 'Start Quiz?',
            text: `Time limit: ${totalTimeSeconds / 60} minutes. The timer starts immediately and cannot be paused.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d', // Secondary color for cancel
            confirmButtonText: '<i class="fa-solid fa-play me-1"></i> Yes, Start Now',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                startQuizInterface(); // Proceed to start the quiz UI
            }
        });
    }

    /**
     * Handles the confirmation and deletion process for retaking the quiz.
     */
    function confirmAndDeletePrevious() {
         Swal.fire({
            title: 'Retake Quiz?',
            html: `Your previous result (if any) will be <strong>permanently deleted</strong>. Are you sure you want to start a new attempt?`, // Use html for bold
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Red for destructive action
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-trash-alt me-1"></i> Yes, Delete & Retake',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting Previous Result...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request to delete the previous record
                fetch(deleteResultRoute, {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        // Try to parse error message from server if available
                        return response.json().then(errData => {
                             throw new Error(errData.message || `Server error: ${response.status}`);
                        }).catch(() => {
                            // Fallback if response is not JSON or has no message
                            throw new Error(`Failed to delete previous result. Status: ${response.status}`);
                        });
                    }
                    return response.json(); // Parse JSON only if response is ok
                })
                .then(data => {
                    Swal.close(); // Close loading indicator
                    if (data.success) {
                        // Previous result deleted successfully, now confirm starting the new attempt
                        confirmAndStartQuiz();
                    } else {
                        // Deletion failed (server-side logic)
                        Swal.fire({
                            title: 'Deletion Failed',
                            text: data.message || 'Could not delete the previous record. Please try again.',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    Swal.close(); // Close loading indicator
                    console.error("Error deleting previous result:", error);
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'An unexpected error occurred while trying to delete the previous result. Check your network connection.',
                        icon: 'error'
                    });
                });
            }
        });
    }


    /**
     * Submits the quiz, calculates score, displays feedback and report, and saves result.
     */
    function submitQuiz() {
        if (quizFinished) return; // Prevent multiple submissions
        quizFinished = true;
        clearInterval(timerInterval); // Stop the timer
        stopAllSounds(); // Stop any warning sounds

        // Final timer appearance
        if(timerElement) {
            timerElement.classList.remove("bg-primary", "bg-warning", "bg-danger", "text-dark", "text-white");
            if (timeLeft <= 0) {
                timerElement.classList.add("bg-danger", "text-white");
                timerElement.innerHTML = `<i class="fa-solid fa-clock me-1"></i> 0:00`;
            } else {
                timerElement.classList.add("bg-success", "text-white");
                // Keep showing the time left when submitted manually
                 const minutes = Math.floor(timeLeft / 60);
                 const seconds = timeLeft % 60;
                 timerElement.innerHTML = `<i class="fa-regular fa-clock me-1"></i> ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
        }

        // Hide pagination buttons
        if (paginationContainer) paginationContainer.style.display = 'none';

        // --- Evaluate Answers and Provide Feedback ---
        let correctCount = 0;
        let wrongAnswersCount = 0;
        let unansweredCount = 0;

        allQuestionCards.forEach(card => {
            const header = card.querySelector('.card-header');
            const feedbackDiv = card.querySelector('.question-feedback');
            const options = card.querySelectorAll('.form-check-input');
            const labels = card.querySelectorAll('.form-check-label');
            const questionIdInput = card.querySelector('input[type="radio"]'); // Get any radio to find name
            if (!questionIdInput) return; // Skip if card has no inputs

            const questionName = questionIdInput.name; // e.g., "q123"
            const correctValue = correctAnswers[questionName]; // Correct option value ('option_a', etc.)

            const selectedInput = card.querySelector(`input[name="${questionName}"]:checked`);

             // Find the text of the correct answer
            const correctRadio = card.querySelector(`input[value="${correctValue}"]`);
            let correctAnswerText = 'N/A';
             if (correctRadio) {
                  const correctLabel = card.querySelector(`label[for="${correctRadio.id}"]`);
                  correctAnswerText = correctLabel ? correctLabel.textContent : 'N/A';
             }


            // Reset styles first
            header.classList.remove('bg-primary', 'bg-success', 'bg-danger', 'bg-secondary');
             labels.forEach(label => {
                  label.classList.remove('text-success', 'fw-bold', 'text-danger', 'text-decoration-line-through');
             });


             if (feedbackDiv) feedbackDiv.style.display = 'block'; // Show feedback area

            if (!selectedInput) {
                unansweredCount++;
                header.classList.add('bg-secondary'); // Gray for unanswered
                if (feedbackDiv) feedbackDiv.innerHTML = `<span class="text-warning fw-bold">❓ Unanswered.</span> Correct answer: <strong class="text-success">${correctAnswerText}</strong>`;
                const correctLabelElement = card.querySelector(`label[for="${correctRadio?.id}"]`);
                if(correctLabelElement) correctLabelElement.classList.add('text-success', 'fw-bold'); // Highlight correct
            } else if (selectedInput.value === correctValue) {
                correctCount++;
                header.classList.add('bg-success'); // Green for correct
                if (feedbackDiv) feedbackDiv.innerHTML = `<span class="text-success fw-bold">✔️ Correct!</span>`;
                const correctLabelElement = card.querySelector(`label[for="${selectedInput.id}"]`);
                 if(correctLabelElement) correctLabelElement.classList.add('text-success', 'fw-bold');
            } else {
                wrongAnswersCount++;
                header.classList.add('bg-danger'); // Red for incorrect
                if (feedbackDiv) feedbackDiv.innerHTML = `<span class="text-danger fw-bold">❌ Incorrect.</span> Correct answer: <strong class="text-success">${correctAnswerText}</strong>`;
                const wrongLabelElement = card.querySelector(`label[for="${selectedInput.id}"]`);
                 if(wrongLabelElement) wrongLabelElement.classList.add('text-danger', 'text-decoration-line-through');
                 const correctLabelElement = card.querySelector(`label[for="${correctRadio?.id}"]`);
                 if(correctLabelElement) correctLabelElement.classList.add('text-success', 'fw-bold'); // Highlight correct
            }

            // Disable all options for this question
            options.forEach(input => input.disabled = true);

            // Ensure all question cards are visible now to show the full review
            card.style.display = 'block';
        });

        // --- Calculate Final Results ---
        const percentage = totalQuestions > 0 ? Math.round((correctCount / totalQuestions) * 100) : 0;
        const timeTaken = totalTimeSeconds - (timeLeft >= 0 ? timeLeft : -1); // Ensure timeTaken isn't negative if timeLeft went below 0 slightly
        const timeTakenMinutes = Math.floor(timeTaken / 60);
        const timeTakenSeconds = timeTaken % 60;
        const passed = percentage >= passingScore;

        // --- Display Completion Message, Report, and Navigation ---
        if (completionMessageDiv) {
             completionMessageDiv.textContent = `🎉 Quiz Completed! ${passed ? 'Congratulations, you passed!' : 'You did not meet the passing score.'}`;
             completionMessageDiv.className = `alert ${passed ? 'alert-success' : 'alert-warning'} text-center mt-3`; // Change alert type based on pass/fail
             completionMessageDiv.style.display = 'block';
        }

        if (reportContainerDiv) {
             reportContainerDiv.innerHTML = `
                <h4 class="text-center text-primary mb-3 mt-4 border-top pt-3">📊 Quiz Report</h4>
                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-bordered text-center align-middle mb-0">
                        <tbody>
                            <tr><th class="bg-light w-50"><i class="fa-solid fa-list-ol me-1"></i> Total Questions</th><td>${totalQuestions}</td></tr>
                            <tr><th class="bg-light"><i class="fa-regular fa-circle-check me-1 text-success"></i> Correct</th><td><span class="badge bg-success">${correctCount}</span> / ${totalQuestions}</td></tr>
                            <tr><th class="bg-light"><i class="fa-regular fa-circle-xmark me-1 text-danger"></i> Incorrect</th><td><span class="badge bg-danger">${wrongAnswersCount}</span> / ${totalQuestions}</td></tr>
                            <tr><th class="bg-light"><i class="fa-regular fa-circle-question me-1 text-warning"></i> Unanswered</th><td><span class="badge bg-warning text-dark">${unansweredCount}</span> / ${totalQuestions}</td></tr>
                            <tr><th class="bg-light"><i class="fa-regular fa-clock me-1"></i> Time Taken</th><td>${timeTakenMinutes}m ${timeTakenSeconds}s</td></tr>
                            <tr><th class="bg-light"><i class="fa-solid fa-bullseye me-1"></i> Score</th>
                                <td class="fw-bold ${passed ? 'text-success' : 'text-danger'}">
                                    ${percentage}%
                                    <span class="badge ${passed ? 'bg-success' : 'bg-danger'} ms-2">${passed ? 'Passed' : 'Failed'}</span>
                                </td>
                            </tr>
                             <tr><th class="bg-light"><i class="fa-solid fa-percent me-1"></i> Passing Score</th><td>${passingScore}%</td></tr>
                        </tbody>
                    </table>
                </div>
            `;
            reportContainerDiv.style.display = 'block';
        }


        if (navButtonsDiv) {
            navButtonsDiv.innerHTML = `
                {{-- Use a different ID for the post-quiz retake button --}}
                <button class="btn btn-primary me-3" id="postQuizRetakeBtn">
                    <i class="fa-solid fa-arrow-rotate-right me-1"></i> Retake Quiz
                </button>
                <a href="${courseContentRoute}" class="btn btn-secondary">
                   <i class="fa-solid fa-book-open me-1"></i> Continue Course
                </a>
            `;
            navButtonsDiv.style.display = 'block';

             // Add event listener specifically for the *post-quiz* retake button
             const postQuizRetakeBtn = document.getElementById('postQuizRetakeBtn');
             if(postQuizRetakeBtn) {
                 postQuizRetakeBtn.addEventListener('click', confirmAndDeletePrevious); // Reuse the delete confirmation logic
             }
        }


        // --- Save Result to Database via AJAX ---
        const resultData = {
            quiz_id: quizId,
            total_questions: totalQuestions,
            correct_answers: correctCount,
            wrong_answers: wrongAnswersCount,
            unanswered: unansweredCount,
            percentage: percentage,
            time_taken: timeTaken // Send time in seconds
        };

        fetch(storeResultRoute, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify(resultData)
        })
        .then(response => {
             if (!response.ok) {
                 // Attempt to get error details from JSON response
                 return response.json().then(errData => {
                    throw new Error(errData.message || `HTTP error ${response.status}`);
                 }).catch(() => {
                    // Fallback if response isn't JSON
                    throw new Error(`HTTP error ${response.status}`);
                 });
             }
             return response.json();
         })
        .then(data => {
            if (data.success) {
                console.log("Quiz result saved successfully:", data.result);
                 // Optionally show a subtle success message for saving
                 // Toast notification could be good here
            } else {
                 console.error("Error saving result (server):", data.message);
                 Swal.fire({
                     title: 'Save Error',
                     text: `Could not save your quiz result: ${data.message || 'Unknown server error.'}`,
                     icon: 'error'
                 });
            }
        })
        .catch(error => {
             console.error("Error saving result (network/fetch):", error);
             Swal.fire({
                 title: 'Network Error',
                 text: `Could not save your quiz result due to a network issue: ${error.message}`,
                 icon: 'error'
             });
         });
    }


    // --- Event Listeners Setup ---

    // Initial Attempt Button (if it exists)
    if (attemptButton) {
        attemptButton.addEventListener('click', confirmAndStartQuiz);
    }

    // Initial Retake Button (if it exists on previous results card)
    if (retakeButton) {
        retakeButton.addEventListener('click', confirmAndDeletePrevious);
    }

     // Pagination Buttons (only add listeners if they exist)
     if (prevButton) {
         prevButton.addEventListener('click', () => {
             if (currentPage > 0) {
                 showPage(currentPage - 1);
             }
         });
     }
     if (nextButton) {
         nextButton.addEventListener('click', () => {
             if (currentPage < totalPages - 1) {
                 showPage(currentPage + 1);
             }
         });
     }

     // Finish Button (only add listener if it exists)
     if (finishButton) {
         finishButton.addEventListener('click', (event) => {
             event.stopPropagation(); // Good practice
             Swal.fire({
                 title: 'Submit Quiz?',
                 text: "You won't be able to change your answers after submitting.",
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#28a745', // Green for finish
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: '<i class="fa-solid fa-flag-checkered me-1"></i> Yes, Submit',
                 cancelButtonText: 'Cancel'
             }).then((result) => {
                 if (result.isConfirmed) {
                     submitQuiz();
                 }
             });
         });
     }

     // Initial setup if there are questions and the quiz hasn't been auto-started
     // (e.g., if the user hasn't attempted before and the #attemptQuiz button is present)
     // No automatic start needed here, user interaction triggers everything.

}); // End DOMContentLoaded
</script>

{{-- Optional: Preloader Hide Script --}}
<script>
  window.addEventListener('load', function() { // Use load event
      const preloader = document.getElementById("preloader");
      if (preloader) {
          // Add a fade-out effect (optional)
          preloader.style.opacity = '0';
          setTimeout(function() {
              preloader.style.display = "none";
          }, 500); // Match transition duration if using CSS transitions
      }
  });
</script>

@endsection