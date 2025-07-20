@extends('instructor.layout_instructor')

@section('title', 'View Quizzes')

@section('body')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
    <h1 class="mb-0 text-center" style="letter-spacing: 1.5px;">Quiz List</h1>
  </div>

  <!-- Add Quiz Button -->
  <div class="text-center">
    <a href="{{ route('instructor.quizzes.create') }}" class="btn btn-primary">Add Quiz</a>
  </div>

 <!-- Search Bar for Quizzes -->
<div class="search-bar text-center mt-3">
  <form action="{{ route('instructor.quizzes.search') }}" method="GET" class="d-inline-flex">
      <input type="text" name="search" placeholder="Search quizzes..." class="form-control me-2" style="max-width: 300px;" value="{{ request('search') }}">
      
      <select name="course_id" class="form-control me-2" style="max-width: 300px;">
          <option value="">All Courses</option>
          @foreach($courses as $course)
              <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                  {{ $course->title }}
              </option>
          @endforeach
      </select>
      
      <button type="submit" class="btn btn-primary">Search</button>
  </form>
</div>


  <br>
  
  <!-- Quizzes Table Card -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>Quiz Title</th>
              <th>Course</th>
              <th>Chapter</th>
              <th>Time Limit</th>
              <th>Passing Score</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($quizzes as $quiz)
            <tr>
              <td>{{ $quiz->title }}</td>
              <td>{{ $quiz->chapter->course->title ?? 'N/A' }}</td>
              <td>{{ $quiz->chapter->title ?? 'N/A' }}</td>
              <td>{{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'N/A' }}</td>
              <td>{{ $quiz->passing_score ? $quiz->passing_score . '%' : 'N/A' }}</td>
              <td>
                <a href="{{ route('instructor.quizzes.edit', $quiz->id) }}" class="btn btn-success btn-sm">Edit</a>
                <form action="{{ route('instructor.quizzes.destroy', $quiz->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this quiz?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-4">
        {{ $quizzes->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection
