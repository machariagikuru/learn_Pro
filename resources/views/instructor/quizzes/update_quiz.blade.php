@extends('instructor.layout_instructor')

@section('title', 'Edit Quiz')

@section('body')
<div class="container">
    <!-- Page Header -->
    <div class="page-header text-center p-4 bg-white shadow-sm rounded">
        <h1 class="mb-0" style="letter-spacing: 1.5px; font-weight: bold; color: #2c3e50;">Update Quiz</h1>
    </div>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <!-- Update Quiz Form -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <form action="{{ route('instructor.quizzes.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Select Course -->
                    <div class="col-md-6">
                        <label for="course_id" class="form-label">Select Course</label>
                        <select name="course_id" id="course_id" class="form-select" required>
                            <option value="">-- Choose Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ ($quiz->chapter->course->id ?? null) == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Chapter -->
                    <div class="col-md-6">
                        <label for="chapter_id" class="form-label">Select Chapter</label>
                        <select name="chapter_id" id="chapter_id" class="form-select" required>
                            <option value="">-- Choose Chapter --</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}" {{ $quiz->chapter_id == $chapter->id ? 'selected' : '' }}>
                                    {{ $chapter->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quiz Title -->
                    <div class="col-md-6">
                        <label for="title" class="form-label">Quiz Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $quiz->title) }}" placeholder="Enter quiz title" required>
                    </div>

                    <!-- Time Limit -->
                    <div class="col-md-6">
                        <label for="time_limit" class="form-label">Time Limit (minutes)</label>
                        <input type="number" name="time_limit" id="time_limit" class="form-control" value="{{ old('time_limit', $quiz->time_limit) }}" placeholder="Enter time limit" min="1" required>
                    </div>

                    <!-- Passing Score -->
                    <div class="col-md-6">
                        <label for="passing_score" class="form-label">Passing Score (%)</label>
                        <input type="number" name="passing_score" id="passing_score" class="form-control" value="{{ old('passing_score', $quiz->passing_score) }}" placeholder="Enter passing score" min="0" max="100" required>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">Update Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inline CSS -->
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    .container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
    }
    .page-header {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .form-label {
        font-weight: bold;
        color: #555;
    }
    input, select, textarea {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 16px;
    }
    input:focus, select:focus, textarea:focus {
        border-color: #1E90FF;
        box-shadow: 0 0 8px rgba(30, 144, 255, 0.3);
    }
    .btn-primary {
        background-color: #1E90FF;
        font-size: 18px;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0b78e3;
        transform: scale(1.05);
    }
</style>

<!-- AJAX to load chapters based on selected course -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  const courseSelect = document.getElementById('course_id');
  const chapterSelect = document.getElementById('chapter_id');

  courseSelect.addEventListener('change', function(){
      const courseId = this.value;
      chapterSelect.innerHTML = '<option value="">-- Choose Chapter --</option>';
      if(courseId){
          fetch('{{ route("instructor.getChapters") }}?course_id=' + courseId)
              .then(response => {
                  if (!response.ok) throw new Error('Failed to fetch chapters');
                  return response.json();
              })
              .then(data => {
                  data.forEach(chapter => {
                      const option = document.createElement('option');
                      option.value = chapter.id;
                      option.textContent = chapter.title;
                      chapterSelect.appendChild(option);
                  });
              })
              .catch(error => console.log('Error fetching chapters:', error));
      }
  });
});
</script>
@endsection
