@extends('instructor.layout_instructor')
@section('title', ' View Courses')
@section('body')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
    <h1 class="mb-0 text-center" style="letter-spacing: 1.5px;">Course List</h1>
  </div>
  <div class="text-center">
    <a href="{{ url('add_course') }}" class="btn btn-primary">Add Course</a>
  </div> 
  <!-- Search Bar -->
  <div class="search-bar text-center mt-3">
    <form action="{{ url('search_course') }}" method="GET" class="d-inline-flex">
      <input type="text" name="search" placeholder="Search courses..." class="form-control me-2" style="max-width: 300px;" value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
  </div>
  <br>
  <!-- Courses Table Card -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>Image</th>
              <th>Short Video</th>
              <th>Title</th>
              <th>Long Title</th>
              <th>Description</th>
              <th>Why Choose This Course?</th>
              <th>Duration (Hours)</th>
              <th>Price</th>
              <th>Rate</th>
              <th>Category</th>
              <th>Video URL</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($courses as $course)
              <tr>
                <td>
                  <img src="{{ asset('courses/' . $course->image) }}" alt="Course Image" class="img-fluid rounded" style="max-width: 150px;">
                </td>
                <td>
                  @if($course->short_video)
                    <video controls class="rounded" style="max-width: 150px;">
                      <source src="{{ asset('courses/' . $course->short_video) }}" type="video/mp4">
                      Your browser does not support the video tag.
                    </video>
                  @else
                    <span>N/A</span>
                  @endif
                </td>
                <td>{{ $course->title }}</td>
                <td>{{ $course->long_title }}</td>
                <td>
                  <span class="short-text">{{ Str::limit($course->description, 50) }}</span>
                  <span class="full-text" style="display: none;">{{ $course->description }}</span>
                  @if(strlen($course->description) > 50)
                      <button class="toggle-text btn btn-link" style="border: none; background: none; color: blue; cursor: pointer;">
                          Show More
                      </button>
                  @endif
              </td>              
              <td>
                <span class="short-text">{{ Str::limit($course->why_choose_this_course, 50) }}</span>
                <span class="full-text" style="display: none;">{{ $course->why_choose_this_course }}</span>
                @if(strlen($course->why_choose_this_course) > 50)
                    <button class="toggle-text btn btn-link" style="border: none; background: none; color: blue; cursor: pointer;">
                        Show More
                    </button>
                @endif
            </td>
            
                <td>{{ $course->duration }}</td>
                <td>{{ $course->price }}</td>
                <td>{{ $course->rate }}</td>
                <td>{{ $course->category->category_name ?? 'N/A' }}</td>
                <td>
                  @if($course->video_url)
                    <a href="{{ $course->video_url }}" target="_blank">Watch Now</a>
                  @else
                    <span>N/A</span>
                  @endif
                </td>
                <td>
                  <a href="{{ url('update_course', $course->id) }}" class="btn btn-success btn-sm">Update</a>
                  <br>
                  <br>
                  <button onclick="confirmDelete('{{ url('delete_course', $course->id) }}')" class="btn btn-danger btn-sm">Delete</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
      
      
      
      <!-- Pagination -->
      <div class="mt-4">
        {{ $courses->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll(".toggle-text").forEach(function (button) {
          button.addEventListener("click", function () {
              let shortText = this.previousElementSibling.previousElementSibling;
              let fullText = this.previousElementSibling;
              
              if (fullText.style.display === "none") {
                  shortText.style.display = "none";
                  fullText.style.display = "inline";
                  this.textContent = "Show Less";
              } else {
                  shortText.style.display = "inline";
                  fullText.style.display = "none";
                  this.textContent = "Show More";
              }
          });
      });
  });

  function confirmDelete(url) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = url;
      }
    });
  }
</script>

@endsection
