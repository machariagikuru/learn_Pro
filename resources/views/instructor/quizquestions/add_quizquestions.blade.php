@extends('instructor.layout_instructor')

@section('title', 'Add Quiz Questions')

@section('body')
<div class="page-content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
        <h1 class="mb-0 text-center" style="letter-spacing: 1.5px;">Add Quiz Questions</h1>
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

        <form class="add" action="{{ route('quizquestions.store') }}" method="POST">
          @csrf

            <!-- Select Quiz -->
            <div>
            <label for="quiz_id">Select Quiz:</label>
            <select name="quiz_id" id="quiz_id" class="modern-select" required>
              <option value="">-- Choose Quiz --</option>
              @foreach($quizzes as $quiz)
              <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
              @endforeach
            </select>
            </div>

          <!-- Question Text -->
          <div>
            <label for="question_text">Question:</label>
            <textarea name="question_text" id="question_text" class="form-control" rows="3" placeholder="Enter question text" required></textarea>
          </div>

         <!-- Correct Option -->
<div>
  <label for="correct_option">Correct Option:</label>
  <select name="correct_option" id="correct_option" class="form-control" required>
      <option value="">-- Select Correct Option --</option>
      <option value="option_a">Option A</option>
      <option value="option_b">Option B</option>
      <option value="option_c">Option C</option>
      <option value="option_d">Option D</option>
  </select>
</div>


          <!-- Option A -->
          <div>
            <label for="option_a">Option A:</label>
            <input type="text" name="option_a" id="option_a" class="form-control" placeholder="Enter Option A">
          </div>

          <!-- Option B -->
          <div>
            <label for="option_b">Option B:</label>
            <input type="text" name="option_b" id="option_b" class="form-control" placeholder="Enter Option B">
          </div>

          <!-- Option C -->
          <div>
            <label for="option_c">Option C:</label>
            <input type="text" name="option_c" id="option_c" class="form-control" placeholder="Enter Option C">
          </div>

          <!-- Option D -->
          <div>
            <label for="option_d">Option D:</label>
            <input type="text" name="option_d" id="option_d" class="form-control" placeholder="Enter Option D">
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
