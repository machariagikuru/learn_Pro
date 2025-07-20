@extends('instructor.layout_instructor')

@section('title', 'Add Quiz')

@section('body')
<div class="page-content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
        <h1 class="mb-0 text-center" style="letter-spacing: 1.5px;">Add Quiz</h1>
      </div>
      <div class="div_deg">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
        @endif

        <form class="add" action="{{ route('instructor.quizzes.store') }}" method="POST">
          @csrf
          <div>
            <label for="course_id">Select Course:</label>
            <select name="course_id" id="course_id" class="modern-select" required>
              <option value="">-- Choose Course --</option>
              @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="chapter_id">Select Chapter:</label>
            <select name="chapter_id" id="chapter_id" class="modern-select" required>
              <option value="">-- Choose Chapter --</option>
              <!-- Chapters will be loaded via AJAX -->
            </select>
          </div>

          <div>
            <label for="title">Quiz Title:</label>
            <input type="text" name="title" id="title" placeholder="Enter quiz title" required>
          </div>

          <div>
            <label for="time_limit">Time Limit (minutes):</label>
            <input type="number" name="time_limit" id="time_limit" placeholder="Enter time limit" min="1" value="30" required>
            <small class="text-muted">Minimum 1 minute</small>
          </div>

          <div>
            <label for="passing_score">Passing Score (%):</label>
            <input type="number" name="passing_score" id="passing_score" placeholder="Enter passing score" min="0" max="100" value="70" required>
            <small class="text-muted">Between 0 and 100</small>
          </div>

          <button class="btnsumit" type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Inline CSS (or include in your instructor.css) -->
<style type="text/css">
  /* Centered container */
  .div_deg {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 30px;
  }
  /* Form styling */
  .add {
    width: 100%;
    max-width: 600px;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }
  form div {
    margin-bottom: 20px;
  }
  label {
    display: block;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
  }
  input[type='text'],
  input[type='number'],
  textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }
  input[type='text']:focus,
  input[type='number']:focus,
  textarea:focus {
    border-color: #1E90FF;
    box-shadow: 0 0 8px rgba(30, 144, 255, 0.5);
    outline: none;
  }
  textarea {
    resize: vertical;
    min-height: 120px;
  }
  input[type='file'] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    background: #f9f9f9;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }
  input[type='file']:focus {
    border-color: #1E90FF;
    box-shadow: 0 0 8px rgba(30, 144, 255, 0.5);
    outline: none;
  }
  .btnsumit[type='submit'] {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    background-color: #1E90FF;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .btnsumit[type='submit']:hover {
    background-color: #b1c5d4;
  }
  /* Modern select dropdown styling */
  .modern-select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    background-color: white;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }
  .modern-select:focus {
    border-color: #1E90FF;
    box-shadow: 0 0 8px rgba(30, 144, 255, 0.5);
    outline: none;
  }
</style>

<!-- AJAX to load chapters based on selected course -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const courseSelect = document.getElementById('course_id');
  const chapterSelect = document.getElementById('chapter_id');

  courseSelect.addEventListener('change', function() {
    const courseId = this.value;
    chapterSelect.innerHTML = '<option value="">-- Choose Chapter --</option>';

    if(courseId) {
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
        .catch(error => {
          console.error('Error:', error);
          const option = document.createElement('option');
          option.textContent = 'Error loading chapters';
          chapterSelect.appendChild(option);
        });
    }
  });
});
</script>
