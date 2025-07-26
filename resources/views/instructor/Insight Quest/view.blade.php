@extends('instructor.layout_instructor')

@section('title', 'View Insight Quests')

@section('body')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
    <h1 class="mb-0 text-center text-uppercase" style="letter-spacing: 1.5px;">Insight Quests List</h1>
  </div>
  <div class="text-center">
    <a href="{{ route('insight_quest.add') }}" class="btn btn-primary">Add New Quest</a>
  </div>

  <!-- Search Bar -->
  <div class="search-bar text-center mt-3">
    <form action="{{ route('insight_quest.search') }}" method="GET" class="d-inline-flex">
      <input type="text" name="search" placeholder="Search quests..." class="form-control me-2" style="max-width: 300px;" value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
  </div>
  <br>

  <!-- Quests Table Card -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>Image</th>
              <th>Description</th>
              <th>Course</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($quests as $quest)
              <tr>
                <td>
                  @if($quest->image)
                    <img src="{{ asset('storage/' . $quest->image) }}" alt="Quest Image" style="width: 50px; height: 50px; object-fit: cover;">
                  @else
                    <span class="text-muted">No image</span>
                  @endif
                </td>
                <td>{{ Str::limit($quest->description, 50) }}</td>
                <td>{{ $quest->course->title }}</td>
                <td>{{ $quest->created_at->format('Y-m-d') }}</td>
                <td>
                  <a href="{{ route('insight_quest.edit', $quest->id) }}" class="btn btn-success btn-sm">Edit</a>
                  <form action="{{ route('insight_quest.delete', $quest->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this quest?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="mt-4">
        {{ $quests->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection 