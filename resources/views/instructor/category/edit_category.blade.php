@extends('instructor.layout_instructor')

@section('title', 'Update Category')

@section('body')
<div class="container">
    <!-- Page Header -->
    <div class="page-header text-center p-4 bg-white shadow-sm rounded">
        <h1 class="mb-0" style="letter-spacing: 1.5px; font-weight: bold; color: #2c3e50;">Update Category</h1>
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

    <!-- Update Category Form -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <form action="{{ url('update_category', $data->id) }}" method="POST">
                @csrf

                <!-- Category Name -->
                <div class="mb-3">
                    <label for="category" class="form-label">Category Name:</label>
                    <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $data->category_name) }}" placeholder="Enter category name" required>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-4 py-2">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    .container {
        max-width: 600px;
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
    input {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 16px;
        width: 100%;
    }
    input:focus {
        border-color: #1E90FF;
        box-shadow: 0 0 8px rgba(30, 144, 255, 0.3);
        outline: none;
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

@endsection
