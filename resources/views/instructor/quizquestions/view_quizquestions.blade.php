@extends('instructor.layout_instructor')

@section('title', 'View Quiz Questions')

@section('body')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
    <h1 class="mb-0 text-center" style="letter-spacing: 1.5px;">Quiz Questions</h1>
  </div>

  <!-- Add Question Button -->
  <div class="text-center mb-3">
    <a href="{{ route('quizquestions.create') }}" class="btn btn-primary">Add Question</a>
  </div>

  <!-- Search Bar for Quiz Questions -->
<div class="search-bar text-center mt-3">
  <form action="{{ route('quizquestions.search') }}" method="GET" class="d-inline-flex">
      <!-- Text search for question text -->
      <input 
          type="text" 
          name="search" 
          placeholder="Search questions..." 
          class="form-control me-2" 
          style="max-width: 300px;" 
          value="{{ request('search') }}"
      >
      
      <!-- Dropdown to filter by quiz -->
      <select name="quiz_id" class="form-control me-2" style="max-width: 300px;">
          <option value="">All Quizzes</option>
          @foreach($quizzes as $quiz)
              <option 
                  value="{{ $quiz->id }}" 
                  {{ request('quiz_id') == $quiz->id ? 'selected' : '' }}
              >
                  {{ $quiz->title }}
              </option>
          @endforeach
      </select>
      
      <button type="submit" class="btn btn-primary">Search</button>
  </form>
</div>
<br>

  <br>

  <!-- Quiz Questions Table Card -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-primary">
            <tr>

              <th>Question</th>
              <th>Correct Option</th>
              <th>Option A</th>
              <th>Option B</th>
              <th>Option C</th>
              <th>Option D</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($quizQuestions as $question)
            <tr>
              <td>{{ $question->question_text }}</td>
              <td>{{ $question->correct_option }}</td>
              <td>{{ $question->option_a }}</td>
              <td>{{ $question->option_b }}</td>
              <td>{{ $question->option_c }}</td>
              <td>{{ $question->option_d }}</td>
              <td>
                <a href="{{ route('quizquestions.edit', $question->id) }}" class="btn btn-success btn-sm">Edit</a>
                <br>
                <br>
                <form action="{{ route('quizquestions.destroy', $question->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="mt-4">
        {{ $quizQuestions->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection
