@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<br>
<!-- Course Categories -->
<div class="mb-4">
    <button id="dropdownToggle" class="btn btn-primary text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: aliceblue;">
        All
    </button>
    <ul id="categoriesDropdown" class="dropdown-menu">
        <!-- عند تحميل الصفحة، سيتم إضافة عنصر "All" افتراضياً -->
        <li>
            <a class="dropdown-item active" href="{{ route('courses') }}">All</a>
        </li>
        <!-- سيتم إضافة باقي الفئات هنا باستخدام AJAX -->
    </ul>
</div>

<!-- Courses Grid -->
<div class="row" id="courses-container">
    @foreach ($course as $courseItem)
        <div class="col-md-4 mb-4">
            <div class="card card-data p-3 d-flex flex-column h-100">
                <span class="text-primary">{{ $courseItem->title }}</span>
                <a href="{{ route('incourse', $courseItem->id) }}" class="text-decoration-none text-dark flex-grow-1">
                    <img src="{{ asset('courses/' . $courseItem->image) }}" class="img-fluid rounded my-2" alt="{{ $courseItem->title }}">
                    <h5 class="fw-bold">{{ $courseItem->long_title }}</h5>
                    <p>{{ $courseItem->description }}</p>
                </a>
                <span class="fw-bold">{{ $courseItem->rate }} ⭐</span>
            </div>
        </div>
    @endforeach
</div>

<div class="row d-flex justify-content-center">
    @if (request('search'))
        {{ $course->appends(request()->input())->links() }}
    @else
        @if($course->hasMorePages())
            <button id="showMoreButton" class="btn btn-outline-primary" style="width: 8%;">
                More
            </button>
        @endif
    @endif
</div>

@endsection

@section('scripts')
<!-- تأكد من تحميل jQuery و bootstrap.bundle.min.js -->
<script>
    let currentPage = 1;
    let currentCategory = 'all';
    let loading = false;

    // عند تحميل الصفحة، استدعاء AJAX لتحميل الفئات
    $(document).ready(function() {
        // Enable Bootstrap dropdown functionality
        $('#dropdownToggle').on('click', function() {
            $('#categoriesDropdown').toggleClass('show');
            $(this).attr('aria-expanded', $('#categoriesDropdown').hasClass('show'));
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.mb-4').length) {
                $('#categoriesDropdown').removeClass('show');
                $('#dropdownToggle').attr('aria-expanded', 'false');
            }
        });

        // Load categories
        $.ajax({
            url: "{{ route('categories.get') }}",
            method: 'GET',
            dataType: 'json',
            success: function(categories) {
                let dropdown = $("#categoriesDropdown");
                // إضافة كل فئة كرابط في الـ Dropdown
                $.each(categories, function(index, category) {
                    let url = "{{ url('/courses/category') }}/" + category.id;
                    dropdown.append(
                        '<li><a class="dropdown-item" href="' + url + '">' + category.category_name + '</a></li>'
                    );
                });

                // Add click handler for category links
                dropdown.find('a.dropdown-item').on('click', function(e) {
                    e.preventDefault();
                    let selectedCategory = $(this).text();
                    $('#dropdownToggle').text(selectedCategory);
                    $('#categoriesDropdown').removeClass('show');
                    
                    if ($(this).attr('href') === "{{ route('courses') }}") {
                        window.location.href = "{{ route('courses_page') }}";
                    } else {
                        window.location.href = $(this).attr('href');
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error loading categories:", error);
            }
        });

        // Handle More button click
        $('#showMoreButton').on('click', function() {
            if (loading) return;
            
            loading = true;
            currentPage++;
            
            $.ajax({
                url: "{{ route('courses.loadMore') }}",
                method: 'GET',
                data: {
                    page: currentPage,
                    category: currentCategory
                },
                success: function(response) {
                    $('#courses-container').append(response.html);
                    
                    // Update More button visibility
                    if (!response.hasMorePages) {
                        $('#showMoreButton').hide();
                    }
                    
                    loading = false;
                },
                error: function(xhr, status, error) {
                    console.error("Error loading more courses:", error);
                    loading = false;
                }
            });
        });
    });
</script>
@endsection
