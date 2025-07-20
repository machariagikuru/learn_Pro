@extends('instructor.layout_instructor')

@section('title', 'Add Lesson')

@section('body')
<div class="page-content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
        <h1 class="mb-0 text-center " style="letter-spacing: 1.5px;">Add Lesson </h1>
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
        <form class="add" action="{{ route('store.lesson') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <!-- Select Course Dropdown -->
          <div>
            <label for="course_id">Select Course</label>
            <select name="course_id" id="course_id" class="modern-select">
              <option value="">-- Choose Course --</option>
              @foreach ($courses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
              @endforeach
            </select>
          </div>
          <!-- Select Chapter Dropdown -->
          <div>
            <label for="chapter_id">Select Chapter</label>
            <select name="chapter_id" id="chapter_id" class="modern-select">
              <option value="">-- Choose Chapter --</option>
              <!-- Chapters will be populated via AJAX -->
            </select>
          </div>
          <!-- Lesson Title -->
          <div>
            <label for="title">Lesson Title:</label>
            <input type="text" name="title" id="title" placeholder="Enter lesson title">
          </div>
          <!-- Lesson Description -->
          <div>
            <label for="description">Lesson Description:</label>
            <textarea name="description" id="description" placeholder="Enter lesson description"></textarea>
          </div>
          <!-- Long Description -->
          <div>
            <label for="long_description">Long Description:</label>
            <textarea name="long_description" id="long_description" placeholder="Enter long description">{{ old('long_description') }}</textarea>
          </div>
          <!-- Video URL (optional) -->
          <div>
            <label for="video_url">Video URL (optional):</label>
            <input type="text" name="video_url" id="video_url" placeholder="Enter video URL or YouTube link" oninput="toggleFileInput()">
          </div>
          <!-- Upload Video (optional) -->
          <div>
            <label for="video_file">Upload Video (optional):</label>
            <input type="file" name="video_file" id="video_file" accept="video/*" onchange="toggleUrlInput()">
          </div>
          <!-- Order (optional)
          <div>
            <label for="order">Order (optional):</label>
            <input type="number" name="order" id="order" placeholder="Enter order">
          </div> -->
          <button class="btn" type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <!-- jQuery is required for the AJAX call -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      // When the course dropdown changes, fetch its chapters via AJAX
      $('#course_id').on('change', function(){
        var courseId = $(this).val();
        if(courseId) {
          $.ajax({
            url: '/get-chapters/' + courseId, // Ensure this route is defined in your routes/web.php
            type: 'GET',
            dataType: 'json',
            success: function(data) {
              $('#chapter_id').empty();
              $('#chapter_id').append('<option value="">-- Choose Chapter --</option>');
              $.each(data, function(key, chapter){
                $('#chapter_id').append('<option value="'+ chapter.id +'">'+ chapter.title +'</option>');
              });
            },
            error: function() {
              alert('Error retrieving chapters.');
            }
          });
        } else {
          $('#chapter_id').empty();
          $('#chapter_id').append('<option value="">-- Choose Chapter --</option>');
        }
      });
    });
    
    // Optional functions to toggle between video URL and file upload
    function toggleFileInput() {
      if(document.getElementById('video_url').value.trim() !== "") {
        document.getElementById('video_file').disabled = true;
      } else {
        document.getElementById('video_file').disabled = false;
      }
    }
    
    function toggleUrlInput() {
      if(document.getElementById('video_file').value) {
        document.getElementById('video_url').disabled = true;
      } else {
        document.getElementById('video_url').disabled = false;
      }
    }
  </script>
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
  .btn[type='submit'] {
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
  .btn[type='submit']:hover {
    background-color: #0077cc;
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
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
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
