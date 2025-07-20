@extends('instructor.layout_instructor')

@section('title', 'View Chapters')

@section('body')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
    <h1 class="mb-0 text-center text-uppercase" style="letter-spacing: 1.5px;">Chapters List</h1>
  </div>
  <div class="text-center">
    <a href="{{ route('show.add.chapter') }}" class="btn btn-primary">Add Chapter</a>
  </div> 
  <!-- Search Bar for Chapters -->
<div class="search-bar text-center mt-3">
  <form action="{{ url('search_chapter') }}" method="GET" class="d-inline-flex">
    <input type="text" name="search" placeholder="Search chapters..." class="form-control me-2" style="max-width: 300px;" value="{{ request('search') }}">
    <button type="submit" class="btn btn-primary">Search</button>
  </form>
</div>
<br>

  <!-- Chapters Table Card -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <!-- Filter Dropdown -->
      <div class="mb-3">
        <form action="{{ route('view_chapters') }}" method="GET" id="courseFilterForm">
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
      
      <!-- Chapters Table -->
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>Course</th>
              <th>Chapter Title</th>
              <th>Description</th>
              <!-- <th>Order</th> -->
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($chapters as $chapter)
              <tr>
                <td>{{ $chapter->course->title ?? 'N/A' }}</td>
                <td>{{ $chapter->title }}</td>
                <td>{{ $chapter->description }}</td>
                <!-- <td>{{ $chapter->order }}</td> -->
                <td>
                  <a href="{{ route('update_chapter', $chapter->id) }}" class="btn btn-success btn-sm">Update</a>
                </td>
                <td>
                  <a href="{{ route('delete_chapter', $chapter->id) }}" class="btn btn-danger btn-sm">Delete</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="mt-4">
        {{ $chapters->links('pagination::bootstrap-5') }}
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
