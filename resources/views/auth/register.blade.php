<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - LearnPro</title>
    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('Photos/logomark.svg') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/signup-style.css') }}">

    {{-- Inline styles from old code (keep if needed, otherwise rely on signup-style.css) --}}
    <style>
        /* You can copy relevant styles from the old inline <style> block here */
        /* Or ensure they are covered in signup-style.css */
        /* For example: */
        .logo-text {
          font-family: 'Sen', sans-serif; /* Assuming Sen font is loaded or fallback works */
          color: #007EF8; /* Or use --lp-primary-learnpro */
        }
         /* Adjust background if the external CSS doesn't match exactly */
        body.signup-body {
            background-color: #F4F7FC; /* From old code */
        }
    </style>

</head>
<body class="signup-body">
    <!-- Include Loading Overlay Component -->
    @include('components.loading-overlay')

     <!-- Decorative Images -->
    <img src="{{ asset('Photos/Rectangle.jpg') }}" alt="Decorative graphic bottom left" class="decorative-image bottom-left">
    <img src="{{ asset('Photos/Rectangle.jpg') }}" alt="Decorative graphic top right" class="decorative-image top-right">
    <!-- End Decorative Images -->

    <div class="signup-container">
        <!-- Logo -->
        <div class="text-center mb-4">
            {{-- Using structure from old code's logo section --}}
            <a href="{{ url('/') }}" class="logo text-decoration-none d-inline-flex align-items-center">
                <img src="{{ asset('Photos/logomark.svg') }}" alt="LearnPro Logo" style="width: 30px; height: 30px;"> {{-- Adjusted size slightly --}}
                <div class="fs-3 fw-bold ms-2" style="color: var(--lp-primary-learnpro);">LearnPro</div> {{-- Applied new logo class color --}}
            </a>
        </div>

        <!-- Title -->
        <h1 class="form-title text-center mb-5">Create account</h1>

        <!-- Form -->
        {{-- Integrated method, action, and CSRF from old form --}}
        <form method="POST" action="{{ route('register') }}" onsubmit="showLoadingOverlay()">
            @csrf
            <div class="row mb-3 gy-3"> {{-- Added gy-3 for vertical gap on small screens --}}
                <!-- First Name -->
                <div class="col-md-6">
                    {{-- Using Blade components from old code + new CSS classes --}}
                    <x-input-label for="first_name" class="form-label" :value="__('First name')" />
                    <x-text-input id="first_name" class="form-control form-control-lg signup-input" type="text" name="first_name" :value="old('first_name')" required autofocus />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2 text-danger small" /> {{-- Added text-danger small --}}
                </div>
                <!-- Last Name -->
                <div class="col-md-6">
                    <x-input-label for="last_name" class="form-label" :value="__('Last name')" />
                    <x-text-input id="last_name" class="form-control form-control-lg signup-input" type="text" name="last_name" :value="old('last_name')" required />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2 text-danger small" />
                </div>
            </div>

            <div class="row mb-3 gy-3">
                <!-- Email or Phone -->
                <div class="col-md-6">
                     {{-- Assuming 'email' field handles both email/phone in backend --}}
                    <x-input-label for="email" class="form-label" :value="__('Email or phone number')" />
                    <x-text-input id="email" class="form-control form-control-lg signup-input" type="text" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
                </div>
                <!-- Date of Birth -->
                <div class="col-md-6">
                    <x-input-label for="dob" class="form-label" :value="__('Date of birth')" />
                    <x-text-input id="dob" class="form-control form-control-lg signup-input" type="date" name="dob" :value="old('dob')" required />
                    <x-input-error :messages="$errors->get('dob')" class="mt-2 text-danger small" />
                </div>
            </div>

            <div class="row mb-3 gy-3">
                <!-- Password -->
                <div class="col-md-6">
                    <x-input-label for="password" class="form-label" :value="__('Password')" />
                    <x-text-input id="password" class="form-control form-control-lg signup-input" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
                </div>
                <!-- Confirm Password -->
                <div class="col-md-6">
                    <x-input-label for="password_confirmation" class="form-label" :value="__('Confirm password')" />
                    <x-text-input id="password_confirmation" class="form-control form-control-lg signup-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small" />
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input signup-checkbox" type="checkbox" value="1" id="rememberMe" name="remember">
                    <label class="form-check-label small" for="rememberMe">
                        Remember me
                    </label>
                </div>
                <a href="{{ route('password.request') ?? '#' }}" class="small forgot-link text-decoration-none">Forgot password?</a>
            </div>

            <!-- Terms Agreement -->
            <div class="form-check mb-3">
                <input class="form-check-input signup-checkbox" type="checkbox" value="1" id="agreeTerms" name="terms" required>
                <label class="form-check-label small" for="agreeTerms">
                    I agree to all the <a href="#" class="text-decoration-none">Terms</a> and <a href="#" class="text-decoration-none">Privacy policy</a>
                </label>
                <x-input-error :messages="$errors->get('terms')" class="mt-2 text-danger small" />
            </div>

            <!-- Show Password Checkbox -->
            <div class="form-check mb-4">
                <input class="form-check-input signup-checkbox" type="checkbox" id="showPassword" onclick="togglePasswordVisibility()">
                <label class="form-check-label small" for="showPassword">
                    Show Password
                </label>
            </div>

            <!-- Buttons -->
            <div class="d-grid gap-3 mb-4">
                <button type="submit" class="btn btn-primary btn-lg btn-create-account">Create account</button>

                <a href="{{ url('auth/google') }}" class="btn btn-light btn-lg btn-google" onclick="showLoadingOverlay()">
                    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo" width="20" height="20" class="me-2 align-middle">Sign-in with google
                </a>
            </div>

            <!-- Login Link -->
            <p class="text-center small login-link-text">
                If you have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Log in</a>
            </p>

        </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        confirmPasswordInput.type = type;
    }
    </script>
</body>
</html>