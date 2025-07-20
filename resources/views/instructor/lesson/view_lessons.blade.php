@extends('instructor.layout_instructor')

@php use Illuminate\Support\Str; @endphp

@section('title', 'View Lessons')

@section('body')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
    <h1 class="mb-0 text-center" style="letter-spacing: 1.5px;">Lesson List</h1>
  </div>

  <!-- Add Lesson Button -->
  <div class="text-center">
    <a href="{{ route('add.lesson') }}" class="btn btn-primary">Add Lesson</a>
  </div>

  <!-- Search Bar for Lessons -->
  <div class="search-bar text-center mt-3">
    <form action="{{ url('search_lesson') }}" method="GET" class="d-inline-flex">
      <input type="text" name="search" placeholder="Search lessons..." class="form-control me-2" style="max-width: 300px;" value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
  </div>
  <br>

  <!-- Lessons Table Card -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <!-- Filter Dropdown -->
      <div class="mb-3">
        <form action="{{ route('view_lessons') }}" method="GET" id="courseFilterForm">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <select name="course_id" id="courseSelect" class="form-control">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                  <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                    {{ $course->title }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      
      <!-- Lessons Table -->
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>Lesson Title</th>
              <th>Description</th>
              <th>Long Description</th>
              <th>Video</th>
              <th>Video URL</th>
              <!-- <th>Order</th> -->
              <th>Chapter</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($lessons as $lesson)
              <tr>
                <td>{{ $lesson->title }}</td>
                <td>{{ $lesson->description }}</td>
                <td>
                  @if($lesson->long_description)
                    {{ Str::limit($lesson->long_description, 50) }}
                  @else
                    N/A
                  @endif
                </td>
                <td>
                  @if($lesson->video)
                      <video controls class="rounded" style="max-width: 150px;">
                          <source src="{{ asset($lesson->video) }}" type="video/mp4">
                          Your browser does not support the video tag.
                      </video>
                  @else
                      N/A
                  @endif
              </td>
              
              
                <td>
                  @if($lesson->video_url)
                    <a href="{{ $lesson->video_url }}" target="_blank">Watch Video</a>
                  @else
                    N/A
                  @endif
                </td>
                <!-- <td>{{ $lesson->order }}</td> -->
                <td>{{ $lesson->chapter->title ?? 'N/A' }}</td>
                <td>
                  <a href="{{ route('update_lesson', $lesson->id) }}" class="btn btn-success btn-sm">Update</a>
                </td>
                <td>
                  <a href="{{ route('delete_lesson', $lesson->id) }}" class="btn btn-danger btn-sm">Delete</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-4">
        {{ $lessons->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('courseSelect').addEventListener('change', function() {
      document.getElementById('courseFilterForm').submit();
    });
  });
</script>
@endsection
