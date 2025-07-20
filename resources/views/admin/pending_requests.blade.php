{{-- File: resources/views/admin/pending_requests.blade.php --}}
@extends('admin.layout_admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="container mt-4">
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Pending Instructor Requests</h4>
            <a href="{{ route('admin.users') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-warning">
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Request Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingRequests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->user->first_name }}</td>
                            <td>{{ $request->user->email }}</td>
                            <td>{{ $request->message }}</td>
                            <td>
                                <button class="btn btn-success processRequest" data-requestid="{{ $request->id }}" data-action="approve">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="btn btn-danger processRequest" data-requestid="{{ $request->id }}" data-action="decline">
                                    <i class="fas fa-times"></i> Decline
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No pending requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery & Toastr -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function(){
    $(".processRequest").click(function(){
        var requestId = $(this).data("requestid");
        var action = $(this).data("action");

        $.ajax({
            url: "{{ route('admin.processInstructorRequest') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                request_id: requestId,
                action: action
            },
            success: function(response) {
                toastr.success(response.message, "Success");
                setTimeout(function(){
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                toastr.error("Something went wrong!", "Error");
            }
        });
    });
});
</script>
@endsection
