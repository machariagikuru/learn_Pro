@extends('instructor.layout_instructor')

@section('title', 'Quiz Results')

@section('body')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Quiz Results</h2>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('instructor.quiz.attempts') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Course</label>
                    <select name="course_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Quiz Results List -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Quiz</th>
                            <th>Course</th>
                            <th>Total Questions</th>
                            <th>Correct Answers</th>
                            <th>Wrong Answers</th>
                            <th>Unanswered</th>
                            <th>Percentage</th>
                            <th>Time Taken</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attempts as $attempt)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($attempt->user->profile_photo_path)
                                        <img src="{{ Storage::url($attempt->user->profile_photo_path) }}" 
                                             class="rounded-circle me-2" 
                                             width="40" 
                                             height="40"
                                             alt="Profile Photo">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($attempt->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $attempt->user->name }}</div>
                                        <small class="text-muted">{{ $attempt->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $attempt->quiz->title }}</td>
                            <td>{{ $attempt->quiz->chapter->course->title }}</td>
                            <td>{{ $attempt->total_questions }}</td>
                            <td>{{ $attempt->correct_answers }}</td>
                            <td>{{ $attempt->wrong_answers }}</td>
                            <td>{{ $attempt->unanswered }}</td>
                            <td>
                                <span class="badge bg-{{ $attempt->percentage >= 70 ? 'success' : ($attempt->percentage >= 50 ? 'warning' : 'danger') }}">
                                    {{ $attempt->percentage }}%
                                </span>
                            </td>
                            <td>{{ $attempt->time_taken }} min</td>
                            <td>{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <button type="button" 
                                        class="btn btn-primary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailsModal{{ $attempt->id }}">
                                    View Details
                                </button>
                            </td>
                        </tr>

                        <!-- Details Modal -->
                        <div class="modal fade" id="detailsModal{{ $attempt->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Quiz Result Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Quiz Details -->
                                        <div class="mb-4">
                                            <h6>Quiz Information</h6>
                                            <p><strong>Title:</strong> {{ $attempt->quiz->title }}</p>
                                            <p><strong>Course:</strong> {{ $attempt->quiz->chapter->course->title }}</p>
                                            <p><strong>Chapter:</strong> {{ $attempt->quiz->chapter->title }}</p>
                                            <p><strong>Attempt Date:</strong> {{ $attempt->created_at->format('M d, Y H:i') }}</p>
                                        </div>

                                        <!-- Performance Summary -->
                                        <div class="mb-4">
                                            <h6>Performance Summary</h6>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <div class="card bg-light">
                                                        <div class="card-body text-center">
                                                            <h6>Total Questions</h6>
                                                            <h4>{{ $attempt->total_questions }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-success text-white">
                                                        <div class="card-body text-center">
                                                            <h6>Correct</h6>
                                                            <h4>{{ $attempt->correct_answers }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-danger text-white">
                                                        <div class="card-body text-center">
                                                            <h6>Wrong</h6>
                                                            <h4>{{ $attempt->wrong_answers }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-warning text-white">
                                                        <div class="card-body text-center">
                                                            <h6>Unanswered</h6>
                                                            <h4>{{ $attempt->unanswered }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Final Score -->
                                        <div class="text-center mb-4">
                                            <h5>Final Score</h5>
                                            <div class="display-4 {{ $attempt->percentage >= 70 ? 'text-success' : ($attempt->percentage >= 50 ? 'text-warning' : 'text-danger') }}">
                                                {{ $attempt->percentage }}%
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button type="button" 
                                                    class="btn btn-secondary" 
                                                    data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="text-muted">No quiz results found</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $attempts->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    .table > :not(caption) > * > * {
        padding: 1rem 1rem;
        background-color: var(--bs-table-bg);
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    }
</style>

@if(session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@endsection 