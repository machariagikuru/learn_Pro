@extends('admin.layout_admin')

@section('content')
<div class="container mt-4">
    <h2>Pending Courses for Approval</h2>
    <div class="row">
        @forelse($pendingCourses as $course)
            <div class="col-md-6 col-lg-4 mb-4" id="course-{{ $course->id }}">
                <div class="card p-3 d-flex flex-column h-100">
                    <!-- معلومات الكورس ورابط لصاحب الكورس وتاريخ الرفع -->
                    <div class="mb-2">
                        <span class="text-primary fw-bold">{{ $course->title }}</span>
                        <small class="d-block text-muted">
                            By: {{ $course->user->first_name ?? 'Unknown' }}
                        </small>
                        <small class="d-block text-muted">
                            Uploaded on: {{ $course->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                    
                    <!-- صورة الكورس والعنوان والوصف المختصر -->
                    <a href="#" class="text-decoration-none text-dark flex-grow-1">
                        <img src="{{ asset('courses/' . $course->image) }}" class="img-fluid rounded my-2" alt="{{ $course->title }}">
                        <h5 class="fw-bold">{{ $course->long_title }}</h5>
                        <p>{{ Str::limit($course->description, 150) }}</p>
                    </a>
                    
                    <!-- تقييم الكورس وأزرار الموافقة والرفض -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="fw-bold">{{ $course->rate }} ⭐</span>
                        <div class="d-flex gap-2">
                            <!-- زر الموافقة -->
                            <form class="approve-form" data-id="{{ $course->id }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <!-- زر الرفض -->
                            <form class="reject-form" data-id="{{ $course->id }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Reject</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- معلومات إضافية مثل السعر والمدة -->
                    <div class="mt-2">
                        <small class="text-muted">Price: ${{ number_format($course->price, 2) }}</small>
                        <br>
                        <small class="text-muted">Duration: {{ $course->duration }} minutes</small>
                    </div>
                </div>
            </div>
        @empty
            <p>No pending courses found.</p>
        @endforelse
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Add a function to show messages
    function showMessage(message, type = 'success') {
        const alertDiv = $('<div>')
            .addClass(`alert alert-${type} alert-dismissible fade show`)
            .attr('role', 'alert')
            .html(`
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `);
        
        $('.container').prepend(alertDiv);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.alert('close');
        }, 5000);
    }

    $('.approve-form').on('submit', function(e) {
        e.preventDefault();
        var courseId = $(this).data('id');
        $.ajax({
            url: '{{ route('admin.approve.course', '') }}/' + courseId,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#course-' + courseId).fadeOut(300, function() {
                    $(this).remove();
                });
                showMessage('Course approved successfully!');
            },
            error: function(response) {
                showMessage('An error occurred while approving the course.', 'danger');
            }
        });
    });

    $('.reject-form').on('submit', function(e) {
        e.preventDefault();
        var courseId = $(this).data('id');
        $.ajax({
            url: '{{ route('admin.reject.course', '') }}/' + courseId,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#course-' + courseId).fadeOut(300, function() {
                    $(this).remove();
                });
                showMessage('Course rejected successfully!');
            },
            error: function(response) {
                showMessage('An error occurred while rejecting the course.', 'danger');
            }
        });
    });
});
</script>
@endsection
