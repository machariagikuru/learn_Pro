@extends('instructor.layout_instructor')

@section('title', 'Update Lesson')

@section('body')
<div class="container">
    <!-- Page Header -->
    <div class="page-header text-center p-4 bg-white shadow-sm rounded">
        <h1 class="mb-0" style="letter-spacing: 1.5px; font-weight: bold; color: #2c3e50;">
            Update Lesson
        </h1>
    </div>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger mt-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success mt-4">{{ session('success') }}</div>
    @endif

    <!-- Update Lesson Form -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <form action="{{ url('update_lesson', $lesson->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Chapter Selection -->
                    <div class="col-md-6">
                        <label for="chapter_id" class="form-label">Chapter:</label>
                        <select name="chapter_id" id="chapter_id" class="form-select">
                            <option value="">Select Chapter</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}" 
                                    {{ (old('chapter_id', $lesson->chapter_id) == $chapter->id) ? 'selected' : '' }}>
                                    {{ $chapter->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order
                    <div class="col-md-6">
                        <label for="order" class="form-label">Order:</label>
                        <input type="number" name="order" id="order" class="form-control"
                               value="{{ old('order', $lesson->order) }}"
                               placeholder="Enter order" min="0">
                    </div> -->

                    <!-- Lesson Title -->
                    <div class="col-md-6">
                        <label for="title" class="form-label">Lesson Title:</label>
                        <input type="text" name="title" id="title" class="form-control"
                               value="{{ old('title', $lesson->title) }}"
                               placeholder="Enter lesson title" required>
                    </div>

                    <!-- Lesson Description -->
                    <div class="col-md-6">
                        <label for="description" class="form-label">Lesson Description:</label>
                        <textarea name="description" id="description" class="form-control"
                                  placeholder="Enter lesson description" rows="3" required>{{ old('description', $lesson->description) }}</textarea>
                    </div>

                    <!-- Long Description -->
                    <div class="col-md-6">
                        <label for="long_description" class="form-label">Long Description:</label>
                        <textarea name="long_description" id="long_description" class="form-control"
                                  placeholder="Enter long description" rows="3">{{ old('long_description', $lesson->long_description) }}</textarea>
                    </div>

                    <!-- Video URL -->
                    <div class="col-md-6" id="video-url-container">
                        <label for="video_url" class="form-label">Video URL (optional):</label>
                        <input type="text" name="video_url" id="video_url" class="form-control"
                               value="{{ old('video_url', $lesson->video_url) }}"
                               placeholder="Enter video URL">
                    </div>

                    <!-- Video File -->
                    <div class="col-md-6" id="video-file-container">
                        <label for="video" class="form-label">Video:</label>
                        <input type="file" name="video" id="video" class="form-control">

                        @if($lesson->video)
                            <!-- Preview Existing Video -->
                            <video controls class="preview-video mt-3">
                                <source src="{{ asset($lesson->video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                </div>

                <!-- Script to Toggle Video URL vs Video File -->
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const videoUrlInput = document.getElementById('video_url');
                        const videoFileInput = document.getElementById('video');
                        const videoUrlContainer = document.getElementById('video-url-container');
                        const videoFileContainer = document.getElementById('video-file-container');

                        function toggleVisibility() {
                            // If there's a URL, hide the file input
                            if (videoUrlInput.value) {
                                videoFileContainer.style.display = 'none';
                            } else {
                                videoFileContainer.style.display = 'block';
                            }

                            // If a file is selected, hide the URL input
                            if (videoFileInput.files.length > 0) {
                                videoUrlContainer.style.display = 'none';
                            } else {
                                videoUrlContainer.style.display = 'block';
                            }
                        }

                        videoUrlInput.addEventListener('input', toggleVisibility);
                        videoFileInput.addEventListener('change', toggleVisibility);

                        toggleVisibility();
                    });
                </script>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">Update Lesson</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Same styling as the Update Course page -->
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
        box-shadow: 0 0 8px rgba(30,144,255,0.3);
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
    .preview-video {
        max-width: 150px;
        border-radius: 8px;
    }
</style>
@endsection
