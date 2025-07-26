@extends('instructor.layout_instructor')

@section('title', 'Add Task')

@section('body')
<div class="page-content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
        <h1 class="mb-0 text-center" style="letter-spacing: 1.5px;">Add Task</h1>
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

        @if (session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif

        <form class="add" action="{{ route('store.task') }}" method="POST">
          @csrf
          
          <div>
            <label for="course_id">Course:</label>
            <select name="course_id" id="course_id" class="modern-select" required>
              <option value="" disabled selected>Select Course</option>
              @foreach ($courses as $course)
                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                  {{ $course->title }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="chapter_id">Chapter:</label>
            <select name="chapter_id" id="chapter_id" class="modern-select" required disabled>
              <option value="" disabled selected>Select Chapter</option>
            </select>
          </div>

          <div>
            <label for="task_title">Task Title:</label>
            <input type="text" name="task_title" id="task_title" value="{{ old('task_title') }}" placeholder="Enter task title" required>
          </div>

          <div>
            <label for="task_description">Task Description:</label>
            <textarea name="task_description" id="task_description" placeholder="Enter task description">{{ old('task_description') }}</textarea>
          </div>

          <div>
            <label for="videos_required_watched">Required Videos:</label>
            <input type="text" name="videos_required_watched" id="videos_required_watched" value="{{ old('videos_required_watched') }}" placeholder="Enter required videos" required>
          </div>

          <button class="btnsumit" type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  .div_deg {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 30px;
  }

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
  textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }

  input[type='text']:focus,
  textarea:focus {
    border-color: #1E90FF;
    box-shadow: 0 0 8px rgba(30, 144, 255, 0.5);
    outline: none;
  }

  textarea {
    resize: vertical;
    min-height: 120px;
  }

  .btnsumit {
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

  .btnsumit:hover {
    background-color: #b1c5d4;
  }

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

  .modern-select:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
  }

  .alert {
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
  }

  .alert-danger {
    background-color: #fff5f5;
    border: 1px solid #fed7d7;
    color: #c53030;
  }

  .alert-success {
    background-color: #f0fff4;
    border: 1px solid #c6f6d5;
    color: #2f855a;
  }

  .alert ul {
    margin: 0;
    padding-left: 20px;
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#course_id').on('change', function() {
        var courseId = $(this).val();
        var chapterSelect = $('#chapter_id');
        
        chapterSelect.prop('disabled', true).empty().append('<option value="">Loading...</option>');
        
        if (courseId) {
            $.ajax({
                url: '/get-chapters/' + courseId,
                type: 'GET',
                success: function(data) {
                    chapterSelect.empty().prop('disabled', false);
                    chapterSelect.append('<option value="" disabled selected>Select Chapter</option>');
                    
                    $.each(data, function(key, chapter) {
                        chapterSelect.append(`<option value="${chapter.id}">${chapter.title}</option>`);
                    });
                },
                error: function() {
                    chapterSelect.empty().append('<option value="" disabled>Error loading chapters</option>');
                }
            });
        } else {
            chapterSelect.empty().prop('disabled', true).append('<option value="" disabled selected>Select Chapter</option>');
        }
    });

    // Trigger change event if course is pre-selected
    if ($('#course_id').val()) {
        $('#course_id').trigger('change');
    }
});
</script>
@endsection