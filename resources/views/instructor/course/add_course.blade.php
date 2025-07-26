@extends('instructor.layout_instructor')

@section('title', 'Add Course')

@section('body')
<div class="page-content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
        <h1 class="mb-0 text-center " style="letter-spacing: 1.5px;">Add Course </h1>
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
        <form class="add" action="{{ url('upload_course') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div>
            <label for="image">Image:</label>
            <input type="file" name="image" id="image">
          </div>
          <!-- Video File Input -->
          <div>
            <label for="video_file">Upload Video:</label>
            <input type="file" name="short_video" id="video_file" accept="video/*" onchange="toggleUrlInput()">
          </div>
          <!-- Video URL Input -->
          <div>
            <label for="video_url">Video URL:</label>
            <input type="text" name="video_url" id="video_url" placeholder="Enter video URL" oninput="toggleFileInput()">
          </div>
          <div>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" placeholder="Enter course title">
          </div>
          <div>
            <label for="long_title">Long Title:</label>
            <input type="text" name="long_title" id="long_title" placeholder="Enter course long title">
          </div>
          <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" placeholder="Enter course description"></textarea>
          </div>
          <div>
            <label for="why_choose_this_course">Why Choose This Course?</label>
            <textarea name="why_choose_this_course" id="why_choose_this_course" placeholder="Enter reasons why this course is beneficial"></textarea>
          </div>
          <div>
            <label for="duration">Duration (in Hours):</label>
            <input type="number" name="duration" id="duration" placeholder="Enter duration">
          </div>
          <div>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" placeholder="Enter price">
          </div>
          <div>
            <label for="rate">Rate:</label>
            <select name="rate" id="rate" class="modern-select">
              <option value="">Select Rating</option>
              @for ($i = 10; $i <= 50; $i++)
                @php $rating = $i / 10; @endphp
                <option value="{{ $rating }}">{{ number_format($rating, 1) }}</option>
              @endfor
            </select>
          </div>
          <div>
            <label for="category">Category:</label>
            <select name="category" id="category" class="modern-select">
              <option value="">Select Category</option>
              @foreach($category as $cat)
                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
              @endforeach
            </select>
          </div>
          <button class="btnsumit" type="submit">Submit</button>
        </form>
      </div>
      
      <!-- Optional Functions to Toggle Between Video URL and File Upload -->
      <script>
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
