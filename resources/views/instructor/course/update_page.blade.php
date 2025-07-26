@extends('instructor.layout_instructor')

@section('title', 'Update Course')

@section('body')
<div class="container">
    <!-- Page Header -->
    <div class="page-header text-center p-4 bg-white shadow-sm rounded">
        <h1 class="mb-0" style="letter-spacing: 1.5px; font-weight: bold; color: #2c3e50;">Update Course</h1>
    </div>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Update Course Form -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <form action="{{ url('update_course', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Course Image -->
                    <div class="col-md-6">
                        <label for="image" class="form-label">Course Image:</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @if($data->image)
                            <img src="{{ asset('courses/' . $data->image) }}" alt="Current Image" class="preview-image mt-2">
                        @endif
                    </div>

                    <!-- Short Video -->
                    <div class="col-md-6" id="video-file-container">
                        <label for="short_video" class="form-label">Short Video:</label>
                        <input type="file" name="short_video" id="short_video" class="form-control">
                        @if($data->short_video)
                            <video controls class="preview-video mt-2">
                                <source src="{{ asset('courses/' . $data->short_video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>

                    <!-- Video URL -->
                    <div class="col-md-6" id="video-url-container">
                        <label for="video_url" class="form-label">Video URL:</label>
                        <input type="url" name="video_url" id="video_url" class="form-control" value="{{ old('video_url', $data->video_url) }}" placeholder="Enter video URL">
                    </div>

                    <!-- Title -->
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $data->title) }}" placeholder="Enter course title" required>
                    </div>

                    <!-- Long Title -->
                    <div class="col-md-6">
                        <label for="long_title" class="form-label">Long Title:</label>
                        <input type="text" name="long_title" id="long_title" class="form-control" value="{{ old('long_title', $data->long_title) }}" placeholder="Enter course long title">
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description:</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Enter course description" rows="3">{{ old('description', $data->description) }}</textarea>
                    </div>

                    <!-- Why Choose This Course -->
                    <div class="col-md-12">
                        <label for="why_choose_this_course" class="form-label">Why Choose This Course?</label>
                        <textarea name="why_choose_this_course" id="why_choose_this_course" class="form-control" placeholder="Enter reasons why this course is beneficial" rows="3">{{ old('why_choose_this_course', $data->why_choose_this_course) }}</textarea>
                    </div>

                    <!-- Duration -->
                    <div class="col-md-6">
                        <label for="duration" class="form-label">Duration (Hours):</label>
                        <input type="number" name="duration" id="duration" class="form-control" value="{{ old('duration', $data->duration) }}" min="1" required>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price:</label>
                        <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $data->price) }}" min="1" required>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <label for="category" class="form-label">Category:</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category', $data->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Rating -->
                    <div class="col-md-6">
                        <label for="rate" class="form-label">Rate:</label>
                        <select name="rate" id="rate" class="form-select">
                            <option value="">Select Rating</option>
                            @for ($i = 1; $i <= 5; $i++)
                                @for ($j = 0; $j < 10; $j++)
                                    @php $rating = $i . '.' . $j; @endphp
                                    @if ($rating > 5) @break @endif
                                    <option value="{{ $rating }}" {{ old('rate', $data->rate) == $rating ? 'selected' : '' }}>
                                        {{ $rating }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videoUrlInput = document.getElementById('video_url');
        const videoFileInput = document.getElementById('short_video');
        const videoUrlContainer = document.getElementById('video-url-container');
        const videoFileContainer = document.getElementById('video-file-container');

        function toggleVisibility() {
            // If there's a URL, hide the file input
            if (videoUrlInput.value.trim()) {
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
    .preview-image, .preview-video {
        max-width: 150px;
        border-radius: 8px;
    }
</style>

@endsection
