@extends('admin.layout_admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-gradient">Revenue Management</h2>
        <div>
            <span class="badge bg-gradient-primary p-2">Total Revenue: ${{ number_format(DB::table('user_courses')->join('courses', 'user_courses.course_id', '=', 'courses.id')->sum('courses.price') * 0.2, 2) }}</span>
            <span class="badge bg-gradient-success p-2 ms-2">Platform Fee: 20%</span>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Revenue Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Monthly Revenue</h5>
                        <i class="fas fa-chart-line text-primary fa-2x"></i>
                    </div>
                    <h3 class="text-gradient-primary mb-2">${{ number_format(DB::table('user_courses')->join('courses', 'user_courses.course_id', '=', 'courses.id')->whereMonth('user_courses.created_at', now()->month)->sum('courses.price') * 0.2, 2) }}</h3>
                    <p class="text-muted mb-0">This Month</p>
                </div>
            </div>
        </div>

        <!-- Total Sales Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Total Sales</h5>
                        <i class="fas fa-dollar-sign text-success fa-2x"></i>
                    </div>
                    <h3 class="text-gradient-success mb-2">${{ number_format(DB::table('user_courses')->join('courses', 'user_courses.course_id', '=', 'courses.id')->sum('courses.price'), 2) }}</h3>
                    <p class="text-muted mb-0">All Time</p>
                </div>
            </div>
        </div>

        <!-- Average Course Price Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-lg hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Average Course Price</h5>
                        <i class="fas fa-tag text-warning fa-2x"></i>
                    </div>
                    <h3 class="text-gradient-warning mb-2">${{ number_format(DB::table('user_courses')->join('courses', 'user_courses.course_id', '=', 'courses.id')->avg('courses.price'), 2) }}</h3>
                    <p class="text-muted mb-0">Per Purchase</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="card border-0 shadow-lg">
        <div class="card-body p-4">
            <h5 class="card-title mb-4 text-gradient">Recent Transactions</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Course</th>
                            <th class="border-0">Student</th>
                            <th class="border-0">Instructor</th>
                            <th class="border-0">Price</th>
                            <th class="border-0">Platform Fee</th>
                            <th class="border-0">Purchase Date</th>
                            <th class="border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(DB::table('user_courses')
                            ->join('courses', 'user_courses.course_id', '=', 'courses.id')
                            ->join('users as instructors', 'courses.user_id', '=', 'instructors.id')
                            ->join('users as students', 'user_courses.user_id', '=', 'students.id')
                            ->select(
                                'courses.title',
                                DB::raw("CONCAT(instructors.first_name, ' ', instructors.last_name) as instructor_name"),
                                DB::raw("CONCAT(students.first_name, ' ', students.last_name) as student_name"),
                                'courses.price',
                                'user_courses.created_at',
                                'user_courses.status'
                            )
                            ->latest('user_courses.created_at')
                            ->take(10)
                            ->get() as $purchase)
                        <tr>
                            <td class="align-middle">{{ $purchase->title }}</td>
                            <td class="align-middle">{{ $purchase->student_name }}</td>
                            <td class="align-middle">{{ $purchase->instructor_name }}</td>
                            <td class="align-middle">${{ number_format($purchase->price, 2) }}</td>
                            <td class="align-middle">${{ number_format($purchase->price * 0.2, 2) }}</td>
                            <td class="align-middle">{{ \Carbon\Carbon::parse($purchase->created_at)->format('M d, Y') }}</td>
                            <td class="align-middle">
                                <span class="badge bg-gradient-success p-2">{{ ucfirst($purchase->status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.text-gradient {
    background: linear-gradient(45deg, #2196F3, #00BCD4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.text-gradient-primary {
    background: linear-gradient(45deg, #2196F3, #00BCD4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.text-gradient-success {
    background: linear-gradient(45deg, #4CAF50, #8BC34A);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.text-gradient-warning {
    background: linear-gradient(45deg, #FFC107, #FF9800);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #2196F3, #00BCD4);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #4CAF50, #8BC34A);
}

.hover-card {
    transition: transform 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
}

.table th {
    font-weight: 600;
    color: #555;
}

.table td {
    color: #666;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.badge {
    border-radius: 8px;
    font-weight: 500;
}
</style>
@endsection 