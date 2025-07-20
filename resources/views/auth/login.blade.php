<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Define character encoding -->
  <meta charset="UTF-8">
  <!-- Ensure proper scaling on mobile devices -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSRF Token for security in forms -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Page title -->
  <title>Sign In - LearnPro</title>
  <!-- Bootstrap CSS for styling and layout -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Favicon (small icon in the browser tab) -->
  <link rel="icon" href="{{ asset('Photos/logomark.svg') }}">
  <!-- Preconnect to Google Fonts for faster loading -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- Include the 'Poppins' font (matching registration page) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
   <!-- Font Awesome (for logo icon) -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- Custom CSS -->
   <link rel="stylesheet" href="{{ asset('css/signin-style-new.css') }}"> <!-- Use a new CSS file -->

</head>
<body class="signin-body">
    <!-- Include Loading Overlay Component -->
    @include('components.loading-overlay')

    <!-- Decorative Images -->
    {{-- Assume these are the same images as the registration page --}}
    <img src="{{ asset('Photos/Rectangle.jpg') }}" alt="Decorative graphic bottom left" class="decorative-image bottom-left">
    <img src="{{ asset('Photos/Rectangle.jpg') }}" alt="Decorative graphic top right" class="decorative-image top-right">
    <!-- End Decorative Images -->

    <!-- Logo Link -->
    <a href="{{ url('/') }}" class="text-decoration-none logo-container">
        <i class="fa-solid fa-asterisk logo-icon"></i>
        <span class="logo-text">LearnPro</span>
    </a>

    <!-- Main form container -->
    <div class="signin-form-container">
        <!-- Main heading -->
        <h1 class="form-title">Sign in</h1>
        <!-- Subheading or instruction -->
        <p class="form-subheading">Please login to continue to your account.</p>

        <!-- Login form -->
        <form method="POST" action="{{ route('login') }}" onsubmit="showLoadingOverlay()">
        @csrf

        <!-- Email or Username Field -->
        <div class="mb-3">
            {{-- No visible label, using placeholder --}}
            <x-text-input id="login" class="form-control form-control-lg signin-input" type="text" name="login" :value="old('login')" required autofocus placeholder="Email or Username"/>
            <x-input-error :messages="$errors->get('login')" class="mt-2 text-danger small" />
        </div>

        <!-- Password Field -->
        <div class="mb-4">
            {{-- No visible label, using placeholder --}}
            <x-text-input id="password" class="form-control form-control-lg signin-input" type="password" name="password" required autocomplete="current-password" placeholder="Password"/>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
        </div>

        <!-- OR Separator -->
        <div class="separator my-3">
            <span>or</span>
        </div>

        <!-- Submit button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg btn-signin">
            Sign In
            </button>
        </div>

        <!-- Google Sign-In option -->
        <div class="d-grid mb-4">
            <a href="{{ url('auth/google') }}" class="btn btn-light btn-lg btn-google" onclick="showLoadingOverlay()">
                <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo" width="20" height="20" class="me-2 align-middle">Sign in with Google
            </a>
        </div>
        </form>

        <!-- Link to registration page -->
        <p class="text-center small create-account-link">
        Need an account? <a href="{{ route('register') }}" class="text-decoration-none fw-medium">Create one</a>
        </p>
    </div>

    <!-- Include Bootstrap JavaScript bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Include footer partial (if exists and needed) -->
    {{-- @include('auth.footer') --}}

</body>
</html>