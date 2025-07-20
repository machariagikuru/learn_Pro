{{-- resources/views/Home/setting.blade.php --}}
@extends('layouts.app')

<style>
    /* Modern card design with gradient background */
    .account-settings-card {
        max-width: 640px;
        margin: 2rem auto;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Modern typography */
    .account-settings-card h2 {
        font-weight: 700;
        margin-bottom: 2rem;
        color: #2c3e50;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .account-settings-card h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 48px;
        height: 3px;
        background: #1E90FF;
        border-radius: 2px;
    }

    /* Enhanced input styling */
    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
    }

    .form-control:focus {
        border-color: #1E90FF;
        box-shadow: 0 4px 12px rgba(30, 144, 255, 0.2);
        transform: translateY(-1px);
    }

    /* Modern label styling */
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }

    /* Gradient button styling */
    .btn-primary {
        background: linear-gradient(135deg, #1E90FF 0%, #0066cc 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 144, 255, 0.3);
    }

    /* Enhanced input group styling */
    .input-group-text {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-right: none;
        color: #6c757d;
        font-weight: 500;
    }

    /* Reset button styling */
    .btn-outline-secondary {
        border: 2px solid #dee2e6;
        color: #6c757d;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        border-color: #adb5bd;
        background: #f8f9fa;
        color: #495057;
    }

    /* Alert styling */
    .alert-success {
        border-radius: 10px;
        background: #e8f5e9;
        border: 2px solid #c8e6c9;
        color: #2e7d32;
    }
</style>


@section('content')
<div class="container py-5">
    <div class="account-settings-card">
        <h2>Account Settings</h2>

        @if (session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            {{-- Email Field --}}
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="name@example.com" 
                    value="{{ old('email', auth()->user()->email) }}" 
                    required
                >
            </div>

            {{-- First Name Field --}}
            <div class="mb-4">
                <label for="first_name" class="form-label">First Name</label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    class="form-control" 
                    placeholder="John" 
                    value="{{ old('first_name', auth()->user()->first_name) }}"
                >
            </div>

            {{-- Last Name Field --}}
            <div class="mb-4">
                <label for="last_name" class="form-label">Last Name</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    class="form-control" 
                    placeholder="Doe" 
                    value="{{ old('last_name', auth()->user()->last_name) }}"
                >
            </div>

            {{-- Phone Field --}}
            <div class="mb-4">
                <label for="phone" class="form-label">Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text">+20</span>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        class="form-control" 
                        placeholder="123 456 7890" 
                        value="{{ old('phone', auth()->user()->phone) }}"
                    >
                </div>
            </div>

            {{-- New Password Field --}}
            <div class="mb-4">
                <label for="new_password" class="form-label">New Password</label>
                <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    class="form-control" 
                    placeholder="••••••••"
                >
                <small class="form-text text-muted mt-1">Minimum 8 characters</small>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <button type="reset" class="btn btn-outline-secondary px-4">Reset</button>
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection