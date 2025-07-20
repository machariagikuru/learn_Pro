@extends('admin.layout_admin')

@section('content')
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table thead th {
        background: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: #4e73df;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .table tbody tr {
        transition: all 0.3s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fc;
    }
    .btn {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-light {
        background-color: #fff;
        border: 1px solid #e3e6f0;
    }
    .btn-light:hover {
        background-color: #f8f9fc;
        border-color: #d1d3e2;
    }
    .form-select {
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }
    .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .action-buttons .btn {
        margin: 0 4px;
    }
    .action-buttons .btn-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        border: none;
    }
    .action-buttons .btn-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        border: none;
    }
    .user-type-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .user-type-badge.user {
        background-color: #e3e6f0;
        color: #4e73df;
    }
    .user-type-badge.instructor {
        background-color: #f0f7ff;
        color: #224abe;
    }
</style>

<!-- Wrapper to center content on the page -->
<div class="d-flex justify-content-center align-items-top" style="min-height: 100vh; background-color: #f8f9fc;">
    <div class="container mt-4">
        <!-- Users List Section -->
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0"><i class="fas fa-users me-2"></i>Users Management</h4>
                    <small class="opacity-75">Manage user accounts and permissions</small>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @if($user->id)
                                <tr>
                                    <td class="fw-bold">#{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                                <small class="text-muted">Member since {{ $user->created_at->format('M Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <select class="form-select changeUserType" data-userid="{{ $user->id }}">
                                            <option value="user" {{ $user->usertype == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="instructor" {{ $user->usertype == 'instructor' ? 'selected' : '' }}>Instructor</option>
                                        </select>
                                    </td>
                                    <td class="action-buttons text-center">
                                        <button class="btn btn-success updateUserType" data-userid="{{ $user->id }}">
                                            <i class="fas fa-save me-1"></i> Update
                                        </button>
                                        <button class="btn btn-danger deleteUser" data-userid="{{ $user->id }}">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function(){
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        // Update user type
        $(".updateUserType").click(function(){
            var userId = $(this).data("userid");
            var userType = $(".changeUserType[data-userid='" + userId + "']").val();
            var button = $(this);

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');

            $.ajax({
                url: "{{ route('admin.updateUserType') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: userId,
                    usertype: userType
                },
                success: function(response) {
                    toastr.success(response.success, "Success");
                    button.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update');
                },
                error: function(xhr) {
                    toastr.error("Something went wrong!", "Error");
                    button.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update');
                }
            });
        });

        // Delete user
        $(".deleteUser").click(function(){
            var userId = $(this).data("userid");
            var button = $(this);

            if(confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Deleting...');

                $.ajax({
                    url: "{{ route('admin.deleteUser') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId
                    },
                    success: function(response) {
                        toastr.success(response.success, "Success");
                        setTimeout(function(){
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        toastr.error("Something went wrong!", "Error");
                        button.prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Delete');
                    }
                });
            }
        });
    });
</script>
@endsection
