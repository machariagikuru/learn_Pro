@extends('instructor.layout_instructor')

@section('title', 'Insight Quest Submissions')

@section('body')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Insight Quest Submissions</h2>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('insight_quest.submissions') }}" method="GET" class="row g-3">
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
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Quest</th>
                            <th>Course</th>
                            <th>Submitted At</th>
                            <th>Status</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($submission->user->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $submission->user->first_name ?? 'User' }}</div>
                                        <small class="text-muted">{{ $submission->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $submission->insightQuest->description ? Str::limit($submission->insightQuest->description, 40) : '-' }}</td>
                            <td>{{ $submission->insightQuest->course->title ?? '-' }}</td>
                            <td>{{ $submission->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $submission->status == 'pending' ? 'warning' : 'success' }}">
                                    {{ ucfirst($submission->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                @if(isset($submission->grade))
                                    <span class="badge bg-{{ $submission->grade >= 70 ? 'success' : ($submission->grade >= 50 ? 'warning' : 'danger') }}">
                                        {{ $submission->grade }}%
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $submission->id }}">
                                    Review
                                </button>
                            </td>
                        </tr>

                        <!-- Review Modal -->
                        <div class="modal fade" id="reviewModal{{ $submission->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Review Submission</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Submission Details -->
                                        <div class="mb-4">
                                            <h6>Quest Details</h6>
                                            <p><strong>Description:</strong> {{ $submission->insightQuest->description }}</p>
                                            <p><strong>Course:</strong> {{ $submission->insightQuest->course->title ?? '-' }}</p>
                                        </div>

                                        <!-- Submitted Files -->
                                        <div class="mb-4">
                                            <h6>Submitted Files</h6>
                                            <div class="list-group">
                                                @forelse(($submission->files ?? []) as $file)
                                                    <a href="{{ route('insight_quest.download', basename($file->file_path)) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-download me-2"></i>
                                                            {{ $file->file_name }}
                                                            <small class="text-muted ms-2">(Click to download)</small>
                                                        </div>
                                                        <div>
                                                            @php
                                                                $filePath = storage_path('app/public/' . $file->file_path);
                                                                $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                                            @endphp
                                                            <span class="badge bg-primary rounded-pill me-2">
                                                                {{ number_format($fileSize / 1024, 2) }} KB
                                                            </span>
                                                            <i class="fas fa-download text-primary"></i>
                                                        </div>
                                                    </a>
                                                @empty
                                                    <div class="text-muted">No files submitted</div>
                                                @endforelse
                                            </div>
                                        </div>

                                       

                                        <!-- Grading Form -->
                                        <form action="{{ route('insight_quest.review', $submission->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Grade (0-100)</label>
                                                <input type="number" name="grade" class="form-control" min="0" max="100" value="{{ $submission->grade }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Feedback</label>
                                                <textarea name="feedback" class="form-control" rows="4" required>{{ $submission->feedback }}</textarea>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit Review</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">No submissions found</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $submissions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection 