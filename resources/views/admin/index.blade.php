@extends('admin.layout_admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
  .card-link {
    text-decoration: none;
    color: inherit;
    display: block;
  }
  .card-link:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
  }
  .card {
    transition: transform 0.3s ease;
  }
</style>
<!-- Card Section -->
<div class="container mt-4">
  <div class="row g-4">
    <!-- Users Statistics Card -->
    <div class="col-md-3">
      <a href="{{ route('admin.users') }}" class="card-link">
        <div class="card shadow-sm border-0">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <h5 class="card-title mb-0">Total Users</h5>
              @if(\App\Models\User::count() > 0)
                <p class="card-text fs-4">{{ \App\Models\User::count() }}</p>
                <small class="text-muted">+{{ \App\Models\User::whereMonth('created_at', now()->month)->count() }} this month</small>
              @else
                <p class="card-text fs-4">0</p>
                <small class="text-muted">No users yet</small>
              @endif
            </div>
            <div class="icon text-primary">
              <i class="fas fa-users fa-2x"></i>
            </div>
          </div>
          <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ \App\Models\User::count() > 0 ? '70' : '0' }}%"></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Course Statistics Card -->
    <div class="col-md-3">
      <a href="{{ route('admin.courses.details') }}" class="card-link">
        <div class="card shadow-sm border-0">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <h5 class="card-title mb-0">Total Courses</h5>
              @if(\App\Models\Course::count() > 0)
                <p class="card-text fs-4">{{ \App\Models\Course::count() }}</p>
                <small class="text-muted">{{ \App\Models\Course::where('status', 'pending')->count() }} pending</small>
              @else
                <p class="card-text fs-4">0</p>
                <small class="text-muted">No courses yet</small>
              @endif
            </div>
            <div class="icon text-success">
              <i class="fas fa-graduation-cap fa-2x"></i>
            </div>
          </div>
          <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ \App\Models\Course::count() > 0 ? '65' : '0' }}%"></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Revenue Statistics Card -->
    <div class="col-md-3">
      <a href="{{ route('admin.revenue.details') }}" class="card-link">
        <div class="card shadow-sm border-0">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <h5 class="card-title mb-0">Total Revenue</h5>
              @php
                $totalRevenue = DB::table('user_courses')
                    ->join('courses', 'user_courses.course_id', '=', 'courses.id')
                    ->sum('courses.price') * 0.2;
              @endphp
              @if($totalRevenue > 0)
                <p class="card-text fs-4">${{ number_format($totalRevenue, 2) }}</p>
                <small class="text-muted">Platform Fee: 20%</small>
              @else
                <p class="card-text fs-4">$0.00</p>
                <small class="text-muted">No revenue yet</small>
              @endif
            </div>
            <div class="icon text-warning">
              <i class="fas fa-dollar-sign fa-2x"></i>
            </div>
          </div>
          <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ \App\Models\Course::sum('price') > 0 ? '80' : '0' }}%"></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Engagement Statistics Card -->
    <div class="col-md-3">
      <a href="{{ route('admin.students.details') }}" class="card-link">
        <div class="card shadow-sm border-0">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <h5 class="card-title mb-0">Active Students</h5>
              @if(\App\Models\UserCourse::where('status', 'active')->count() > 0)
                <p class="card-text fs-4">{{ \App\Models\UserCourse::where('status', 'active')->count() }}</p>
                <small class="text-muted">{{ \App\Models\UserCourse::whereMonth('created_at', now()->month)->count() }} new enrollments</small>
              @else
                <p class="card-text fs-4">0</p>
                <small class="text-muted">No active students</small>
              @endif
            </div>
            <div class="icon text-danger">
              <i class="fas fa-user-graduate fa-2x"></i>
            </div>
          </div>
          <div class="progress" style="height: 4px;">
            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ \App\Models\UserCourse::where('status', 'active')->count() > 0 ? '75' : '0' }}%"></div>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>

<!-- Bootstrap JavaScript and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
      return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    console.log("Bootstrap and dropdowns are initialized.");
  });
</script>

@endsection