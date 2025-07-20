@extends('admin.layout_admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Courses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Course::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Courses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Course::where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       

    <!-- Courses Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Course Management</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="coursesTable">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Instructor</th>
                            <th>Price</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Course::with('instructor')->latest()->get() as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>{{ $course->title }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle text-primary me-2"></i>
                                    {{ $course->user->first_name }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    ${{ number_format($course->price, 2) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-info me-2"></i>
                                    {{ \App\Models\UserCourse::where('course_id', $course->id)->count() }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $course->status === 'published' ? 'success' : ($course->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td>{{ $course->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.table > :not(caption) > * > * {
    padding: 1rem;
}
</style>

@endsection 