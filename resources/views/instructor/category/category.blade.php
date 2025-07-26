@extends('instructor.layout_instructor')

@section('title', 'View Categories')

@section('body')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
    <h1 class="mb-0 text-center " style="letter-spacing: 1.5px;">Category List</h1>
  </div>
  <!-- Search Bar for Categories -->
  <div class="text-center">
    <a href="{{ route('instructor.category.create') }}" class="btn btn-primary">Add Category</a>
  </div>  
<div class="search-bar text-center mt-3">
  <form action="{{ url('search_category') }}" method="GET" class="d-inline-flex">
    <input type="text" name="search" placeholder="Search categories..." class="form-control me-2" style="max-width: 300px;" value="{{ request('search') }}">
    <button type="submit" class="btn btn-primary">Search</button>
  </form>
</div>
<br>
   <!-- Categories Table -->
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>Category Name</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $data)
            <tr>
              <td>{{ $data->category_name }}</td>
              <td>
                <a href="{{ url('edit_category', $data->id) }}" class="btn btn-success btn-sm">Edit</a>
              </td>
              <td>
                <a href="{{ url('delete_category', $data->id) }}" class="btn btn-danger btn-sm delete-category" data-id="{{ $data->id }}">Delete</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listener to all delete buttons
    document.querySelectorAll('.delete-category').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const deleteUrl = this.getAttribute('href');
            const categoryId = this.getAttribute('data-id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
});
</script>
@endpush
@endsection


