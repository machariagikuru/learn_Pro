@extends('instructor.layout_instructor')

@section('title', 'Add Category')

@section('body')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
    <h1 class="mb-0 text-center " style="letter-spacing: 1.5px;">Add Category</h1>
  </div>
  
  <!-- Category Form Card -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <!-- Display Validation Errors -->
      @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif

      <!-- Display Success Message -->
      @if (session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
      @endif

      <!-- Add Category Form -->
      <form action="{{ route('instructor.category.store') }}" method="POST" id="categoryForm">
        @csrf
        <div class="row justify-content-center">
          <div class="col-md-6">
            <input type="text" name="category" class="form-control" placeholder="Enter Category Name" required>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Add Category</button>
            
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Optional: additional scripts if needed.
</script>
@endsection
