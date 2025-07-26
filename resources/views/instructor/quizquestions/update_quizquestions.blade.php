@extends('instructor.layout_instructor')

@section('title', 'Update Quiz Question')

@section('body')
<div class="container">
    <!-- Page Header -->
    <div class="page-header text-center p-4 bg-white shadow-sm rounded">
        <h1 class="mb-0" style="letter-spacing: 1.5px; font-weight: bold; color: #2c3e50;">Update Quiz Question</h1>
    </div>

    <!-- Display Validation Errors -->
    @if($errors->any())
      <div class="alert alert-danger mt-3">
         <ul class="mb-0">
           @foreach($errors->all() as $error)
             <li>{{ $error }}</li>
           @endforeach
         </ul>
      </div>
    @endif

    <!-- Display Success Message -->
    @if(session('success'))
      <div class="alert alert-success mt-3">
         {{ session('success') }}
      </div>
    @endif

    <!-- Update Quiz Question Form -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <form action="{{ route('quizquestions.update', $quizQuestion->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <!-- Select Quiz -->
                    <div class="col-md-6">
                        <label for="quiz_id" class="form-label">Select Quiz</label>
                        <select name="quiz_id" id="quiz_id" class="form-select" required>
                            <option value="">-- Choose Quiz --</option>
                            @foreach($quizzes as $quiz)
                                <option value="{{ $quiz->id }}" {{ $quizQuestion->quiz_id == $quiz->id ? 'selected' : '' }}>
                                    {{ $quiz->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Question Text -->
                    <div class="col-md-12">
                        <label for="question_text" class="form-label">Question</label>
                        <textarea name="question_text" id="question_text" class="form-control" rows="3" placeholder="Enter question text" required>{{ $quizQuestion->question_text }}</textarea>
                    </div>

                   <!-- Correct Option -->
<div class="col-md-6">
    <label for="correct_option" class="form-label">Correct Option</label>
    <select name="correct_option" id="correct_option" class="form-control" required>
        <option value="">-- Select Correct Option --</option>
        <option value="option_a" {{ $quizQuestion->correct_option == 'option_a' ? 'selected' : '' }}>Option A</option>
        <option value="option_b" {{ $quizQuestion->correct_option == 'option_b' ? 'selected' : '' }}>Option B</option>
        <option value="option_c" {{ $quizQuestion->correct_option == 'option_c' ? 'selected' : '' }}>Option C</option>
        <option value="option_d" {{ $quizQuestion->correct_option == 'option_d' ? 'selected' : '' }}>Option D</option>
    </select>
</div>


                    <!-- Option A -->
                    <div class="col-md-6">
                        <label for="option_a" class="form-label">Option A</label>
                        <input type="text" name="option_a" id="option_a" class="form-control" value="{{ $quizQuestion->option_a }}" placeholder="Enter Option A">
                    </div>

                    <!-- Option B -->
                    <div class="col-md-6">
                        <label for="option_b" class="form-label">Option B</label>
                        <input type="text" name="option_b" id="option_b" class="form-control" value="{{ $quizQuestion->option_b }}" placeholder="Enter Option B">
                    </div>

                    <!-- Option C -->
                    <div class="col-md-6">
                        <label for="option_c" class="form-label">Option C</label>
                        <input type="text" name="option_c" id="option_c" class="form-control" value="{{ $quizQuestion->option_c }}" placeholder="Enter Option C">
                    </div>

                    <!-- Option D -->
                    <div class="col-md-6">
                        <label for="option_d" class="form-label">Option D</label>
                        <input type="text" name="option_d" id="option_d" class="form-control" value="{{ $quizQuestion->option_d }}" placeholder="Enter Option D">
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">Update Question</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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
