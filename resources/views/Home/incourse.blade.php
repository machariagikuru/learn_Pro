@extends('layouts.head')

@section('content')
<!-- Hero Section -->
<section class="hero position-relative text-center text-white">
    <img src="{{ asset('courses/' . $course->image) }}" class="w-100" alt="UX Research" style="height: 450px; object-fit: cover;">
    <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center">
        <h1 class="fw-bold">{{ $course->long_title }}</h1>
        <p>{{ $course->description }}</p>
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold">{{ $course->rate }} ⭐</span>
        </div>
        <a href="{{ url('payment', $course->id) }}" class="btn btn-primary">Enroll Course</a>
        <div class="d-flex justify-content-center align-items-center p-3 border border-3 border-primary bg-white gap-4 mt-4 text-center">
            <p class="fw-bold text-dark mb-0">⏳ Duration: <span class="text-primary">{{ $course->duration }}h</span></p>
            <p class="fw-bold text-dark mb-0">📚 Category: <span class="text-success">{{ $course->category->category_name }}</span></p>
        </div>
    </div>
</section>

<!-- Why Choose This Course -->
<section class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <!-- Video Section -->
            <div class="img-fluid rounded" controls poster="{{ asset('assets/images/full-stack-thumbnail.webp') }}">
                <video width="640" height="400" controls>
                    <source src="{{ asset('courses/' . $course->short_video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
        <div class="col-md-6">
            <h2>Why Choose This Course?</h2>
            <p>{{ $course->why_choose_this_course }}</p>
            <a href="{{ url('payment', $course->id) }}" class="btn btn-primary">Enroll Course</a>
        </div>
    </div>
</section>

<!-- Reviews and Ratings -->
<section class="container my-5">
    <h2>Reviews and Rating</h2>
    <div class="card p-3">
        <div class="d-flex align-items-center">
            <img src="{{ asset('assets/images/no-user.png') }}" class="rounded-circle me-2" width="35" alt="User">
            <input type="text" class="form-control" placeholder="Ask a question or start a post">
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
            <span class="text-warning">☆☆☆☆☆</span>
            <a href="javascript:void(0)" class="btn btn-outline-dark mt-3">
                <i class="fa-regular fa-paper-plane"></i>
            </a>
        </div>
    </div>

    <!-- User Reviews -->
    <div class="mt-4">
        <!-- Example Review Card -->
        <div class="card p-3 mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/images/no-user.png') }}" class="rounded-circle me-2" width="35" alt="User">
                <span class="fw-bold">Jake Thompson</span>
            </div>
            <span class="text-warning">★★★★</span>
            <p>This course gave me a solid foundation in full-stack development! I appreciated the hands-on projects, but I would have liked more advanced topics on backend scalability.</p>
        </div>
        <!-- Add other review cards similarly -->
    </div>
</section>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
    window.onload = function() {
        setTimeout(function() {
            document.getElementById("preloader").style.display = "none";
        }, 2000);

        // Initialize all dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    };
</script>
<script>
    function toggleText() {
        const moreText = document.querySelector('.more-text');
        const btn = document.querySelector('.show-more-btn');
        if (moreText.style.display === "none" || moreText.style.display === "") {
            moreText.style.display = "inline"; // Show text
            btn.textContent = "Show Less"; // Change button text
        } else {
            moreText.style.display = "none"; // Hide text
            btn.textContent = "Show More..."; // Reset button text
        }
    }
</script>
@endsection
