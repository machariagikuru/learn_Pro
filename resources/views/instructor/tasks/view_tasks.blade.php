@extends('instructor.layout_instructor')

@section('title', 'View Tasks')

@section('body')
<div class="page-content">
    <div class="container-fluid py-4">

        {{-- Display Session Feedback Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Main Card for Tasks List --}}
        <div class="card shadow-sm border-light">
            <div class="card-header bg-light border-bottom d-flex flex-wrap justify-content-between align-items-center py-3">
                {{-- Card Title --}}
                <h1 class="mb-0 h5 fw-bold text-primary">Task List</h1>

                {{-- Filters and Actions --}}
                <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
                    {{-- Course Filter Form --}}
                    <form action="{{ route('view.tasks') }}" method="GET" id="courseFilterForm" class="me-md-2">
                        <div class="input-group input-group-sm">
                             <label for="courseSelect" class="input-group-text bg-white border-end-0"><i class="fas fa-filter text-muted"></i></label>
                            <select name="course_id" id="courseSelect" class="form-select form-select-sm border-start-0" aria-label="Filter by Course">
                                <option value="">Filter by Course (All)</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    {{-- Action Buttons --}}
                    <div class="action-buttons">
                        <a href="{{ route('add.task') }}" class="btn btn-sm btn-primary" title="Add New Task Details">
                            <i class="fas fa-plus"></i> Add Task
                        </a>
                        <a href="{{ route('add.task.point') }}" class="btn btn-sm btn-outline-secondary" title="Add Task Points">
                            <i class="fas fa-tasks"></i> Add Points
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 p-md-3"> {{-- Remove padding on small screens if needed --}}
                @if($tasks->isEmpty())
                    <div class="alert alert-info text-center m-3">No tasks found matching your criteria.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0"> {{-- Removed table-striped for cleaner look, add back if preferred --}}
                            <thead class="table-light" style="border-bottom: 2px solid #dee2e6;">
                                <tr>
                                    <th scope="col" class="ps-3">Task Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Videos Req.</th>
                                    <th scope="col">Chapter</th>
                                    <th scope="col">Course</th>
                                    <th scope="col" class="text-center pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td class="ps-3">{{ $task->title }}</td>
                                        <td>{{ Str::limit($task->description, 50, '...') }}</td>
                                        <td>{{ $task->videos_required_watched }}</td>
                                        <td>{{ $task->chapter->title ?? 'N/A' }}</td>
                                        <td>{{ $task->course->title ?? 'N/A' }}</td>
                                        <td class="text-center text-nowrap pe-3 action-cell">
                                            {{-- Edit Task Details & Points --}}
                                            <a href="{{ route('edit.task', $task->id) }}" class="btn btn-success btn-sm me-1" title="Edit Task & Points">
                                                <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Edit</span>
                                            </a>
                                            {{-- Link to Add Point Blocks page (pre-fills task) --}}
                                            <a href="{{ route('add.task.point', ['task_id' => $task->id]) }}" class="btn btn-info btn-sm me-1" title="Add/Manage Point Blocks">
                                                <i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Points</span>
                                            </a>
                                            {{-- Delete Task --}}
                                            <a href="#" class="btn btn-danger btn-sm" title="Delete Task" onclick="confirmDelete({{ $task->id }}); return false;">
                                                <i class="fas fa-trash-alt"></i> <span class="d-none d-md-inline">Delete</span>
                                            </a>
                                            {{-- Hidden Delete Form --}}
                                            <form id="delete-form-{{ $task->id }}" action="{{ route('delete.task', $task->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($tasks->hasPages())
                        <div class="mt-4 d-flex justify-content-center border-top pt-3">
                            {{ $tasks->appends(request()->query())->links('pagination::bootstrap-5') }} {{-- Append query string for filter persistence --}}
                        </div>
                    @endif
                @endif
            </div> {{-- End card-body --}}
        </div> {{-- End card --}}

    </div> {{-- End container-fluid --}}
</div> {{-- End page-content --}}
@endsection

@section('scripts')
{{-- Font Awesome for icons (ensure it's loaded) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
{{-- Bootstrap JS for dismissible alerts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const courseSelect = document.getElementById('courseSelect');
        // Auto-submit form when course filter changes
        if (courseSelect) {
            courseSelect.addEventListener('change', function() {
                document.getElementById('courseFilterForm').submit();
            });
        }
    });

    // Function to confirm task deletion
    function confirmDelete(taskId) {
        if (confirm('Are you sure you want to delete this task and ALL its associated points? This action cannot be undone.')) {
            document.getElementById('delete-form-' + taskId).submit();
        }
    }
</script>
@endsection

@section('styles')
<style>
    .page-content { background-color: #f8f9fa; }
    .card-header .form-select-sm { max-width: 250px; display: inline-block; vertical-align: middle; } /* Limit filter width */
    .card-header .input-group-sm .input-group-text { font-size: .875rem; padding: .25rem 0.5rem; }
    .table th { font-weight: 600; white-space: nowrap; font-size: 0.9rem; text-transform: uppercase; color: #6c757d; }
    .table td { vertical-align: middle; font-size: 0.95rem; }
    .action-cell .btn-sm { padding: 0.2rem 0.5rem; font-size: 0.8rem; } /* Smaller action buttons */
    .action-cell i { /* margin-right: 3px; */ } /* Adjust icon spacing if needed using me-* */
    .pagination .page-link { font-size: 0.9rem; }
    .pagination .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; }
    /* Optional: Add hover effect to table rows */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
        cursor: pointer; /* Indicate row interaction */
    }
</style>
@endsection