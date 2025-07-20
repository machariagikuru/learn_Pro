@extends('instructor.layout_instructor')

@section('body')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Course Enrollment Analysis</h4>
                </div>
                <div class="card-body">
                    <!-- Course Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Enrollments</h5>
                                    <p class="card-text fs-4">{{ $totalEnrollments }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Active Students</h5>
                                    <p class="card-text fs-4">{{ $activeStudents }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Money Flow</h5>
                                    <p class="card-text fs-4">{{ number_format($moneyFlow, 2) }} $</p>
                                    <small>(After 20% platform fee)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Monthly Enrollments ({{ date('Y') }})</h5>
                                    <canvas id="monthlyEnrollmentsChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Course Popularity</h5>
                                    <canvas id="coursePopularityChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Enrollments Table -->
                    <div class="table-responsive mb-4">
                        <h5>Course Performance</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Course Title</th>
                                    <th>Price</th>
                                    <th>Total Enrollments</th>
                                    <th>Total Revenue</th>
                                    <th>Your Earnings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseStats as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td>${{ number_format($course->price, 2) }}</td>
                                    <td>{{ $course->enrollments }}</td>
                                    <td>${{ number_format($course->price * $course->enrollments, 2) }}</td>
                                    <td>${{ number_format($course->price * $course->enrollments * 0.8, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Student Details Table -->
                    <div class="table-responsive">
                        <h5>Student Enrollments</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Enrollment Date</th>
                                    <th>Status</th>
                                    <th>Lessons Watched</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentEnrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->first_name }} {{ $enrollment->last_name }}</td>
                                    <td>{{ $enrollment->email }}</td>
                                    <td>{{ $enrollment->course_title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($enrollment->enrollment_date)->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $enrollment->status === 'active' ? 'success' : 'warning' }}">
                                            {{ $enrollment->status }}
                                        </span>
                                    </td>
                                    <td>{{ $enrollment->lessons_watched }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Enrollments Chart
    const monthlyCtx = document.getElementById('monthlyEnrollmentsChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Enrollments',
                data: @json(array_values($monthlyData)),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Course Popularity Chart
    const popularityCtx = document.getElementById('coursePopularityChart').getContext('2d');
    new Chart(popularityCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($courseStats->pluck('title')) !!},
            datasets: [{
                label: 'Enrollments',
                data: {!! json_encode($courseStats->pluck('enrollments')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endsection 