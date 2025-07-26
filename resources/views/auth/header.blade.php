<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - LearnPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F4F7FC;
            min-height: 100vh;
        }

        .logo-text {
            font-family: 'Sen', sans-serif;
            color: #007EF8;
        }

        .form-container {
            background-color: white;
            border-radius: 10px;
        }

        .btn-create {
            background-color: #1E90FF;
            border-color: #1E90FF;
        }

        .btn-google {
            border-color: #D1D1D1;
        }

        .text-primary {
            color: #1E90FF !important;
        }

        @media (min-width: 992px) {
            .logo-container {
                position: absolute;
                top: 4rem;
                left: 50%;
                transform: translateX(-50%);
            }
        }
    </style>
</head>

<body class="d-flex flex-column justify-content-center align-items-center position-relative py-4">
    <div class="logo-container mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('Photos/logomark.svg') }}" alt="LearnPro Logo" style="width: 40px; height: 40px;">

            <div class="logo-text fs-2 fw-bold ms-2">LearnPro</div>
        </div>
    </div>
    </body>