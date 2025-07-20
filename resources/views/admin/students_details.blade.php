@extends('admin.layout_admin')

@section('content')
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table thead th {
        background: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: #4e73df;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .table tbody tr {
        transition: all 0.3s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fc;
    }
    .btn {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-light {
        background-color: #fff;
        border: 1px solid #e3e6f0;
    }
    .btn-light:hover {
        background-color: #f8f9fc;
        border-color: #d1d3e2;
    }
    .action-buttons .btn {
        margin: 0 4px;
    }
    .action-buttons .btn-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        border: none;
    }
    .action-buttons .btn-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        border: none;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .status-badge.active {
        background-color: #e3f2fd;
        color: #1976d2;
    }
    .status-badge.completed {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    .status-badge.pending {
        background-color: #fff3e0;
        color: #f57c00;
    }
</style>

<!-- Wrapper to center content on the page -->
<div class="d-flex justify-content-center align-items-top" style="min-height: 100vh; background-color: #f8f9fc;">
    <div class="container mt-4">
        <!-- Students List Section -->
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Students Management</h4>
                    <small class="opacity-75">Manage student enrollments and progress</small>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>
            <div class="card-body">
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Active Students</h5>
                                <h3 class="text-primary">{{ \App\Models\UserCourse::where('status', 'active')->count() }}</h3>
                                <p class="text-muted">Currently Enrolled</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Completion Rate</h5>
                                <h3 class="text-success">
                                    @php
                                        $total = \App\Models\UserCourse::count();
                                        $completed = \App\Models\CourseCompletion::count();
                                        $rate = $total > 0 ? ($completed / $total) * 100 : 0;
                                    @endphp
                                    {{ number_format($rate, 1) }}%
                                </h3>
                                <p class="text-muted">Course Completion</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Total Enrollments</h5>
                                <h3 class="text-warning">{{ \App\Models\UserCourse::count() }}</h3>
                                <p class="text-muted">All Time</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Enrolled Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\UserCourse::with(['user', 'course'])->latest()->get() as $enrollment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2">
                                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $enrollment->user->name }}</div>
                                            <small class="text-muted">{{ $enrollment->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $enrollment->course->title }}</td>
                                <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $enrollment->status }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                               
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function(){
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        // Add your JavaScript functionality here
    });
</script>
@endsection 