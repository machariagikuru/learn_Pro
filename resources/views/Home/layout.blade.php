<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'LearnPro | Dashboard')</title>
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/preloader.css') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <script src="https://checkout.stripe.com/checkout.js"></script>
  <link rel="icon" href="{{ asset('Photos/logomark.svg') }}">

  <!-- Custom Styles -->
  <style>
    .today {
      background-color: #0088ff !important;
      color: #fff !important;
      font-weight: bold !important;
      border-radius: 50% !important;
      text-align: center !important;
    }
    .sidebar-hover:hover {
      background-color: #f0f7ff;
      box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);
    }
    .card-data, .card-data:hover {
      transition: background-color 0.2s ease-in-out;
    }
    .card-data:hover {
      background-color: #cee6ff82;
    }
    .card-data {
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .card-data a {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      text-decoration: none;
      color: inherit;
    }
    .card-data img {
      width: 100%;
      height: auto;
      border-radius: 8px;
    }
    .card-data span.fw-bold {
      text-align: left;
      margin-top: auto;
    }
  </style>
</head>
<body class="bg-gray-50">
  <div class="d-flex">
    <!-- Sidebar -->
    <aside class="w-64 h-screen bg-white shadow-md fixed left-0 top-0 transition-all duration-300">
      <!-- Logo Section -->
      <a href="{{ route('dashboard') }}" class="pl-4 pr-6 py-4 border-b flex items-center">
        <img src="{{ asset('Photos/logomark.svg') }}" alt="LearnPro Logo" class="w-8 h-8 mr-2">
        <h1 class="text-2xl font-bold text-gray-800">LearnPro</h1>
      </a>
      <nav class="p-4 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="block p-3 text-gray-600 rounded-lg transition-colors sidebar-hover">
          <i class='bx bxs-dashboard mr-2'></i> Dashboard
        </a>
        <!-- Courses -->
        <a href="{{ route('courses_page') }}" class="block p-3 text-gray-600 rounded-lg transition-colors sidebar-hover">
          <i class='bx bxs-book-alt mr-2'></i> Courses
        </a>
        <!-- Settings -->
        <a href="route('profile.edit')" class="block p-3 bg-blue-50 text-blue-600 font-medium rounded-lg">
          <i class='bx bxs-cog mr-2'></i> Settings
        </a>
        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
          @csrf
          <button type="submit" class="w-full text-left p-3 text-gray-600 rounded-lg transition-colors sidebar-hover">
            <i class='bx bx-log-out mr-2'></i> Log Out
          </button>
        </form>
      </nav>
    </aside>
    
    <!-- Main Content Area -->
    <div class="content p-4 flex-grow-1" style="margin-left: 16rem;"> <!-- تعديل لإزاحة المحتوى عن السايدبار -->
      <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="fs-1 fw-bold">
            Hello, <span class="text-primary">{{ Auth::user()->first_name }}</span> 👋
          </h4>
          <h6 class="text-secondary">Let's learn something new today!</h6>
        </div>
        <div class="d-flex align-items-center gap-4">
          <div class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Search from courses...">
            <a href="javascript:void(0)" class="btn btn-secondary px-2 rounded-5">
              <i class="fas fa-search"></i>
            </a>
          </div>
          <a href="javascript:void(0)" class="btn btn-white notification-bell border border-2">
            <i class="fa-solid fa-bell"></i>
            <span class="badge"></span>
          </a>
        </div>
      </header>
      <hr>
      
      <!-- Content Section: ضع هنا المحتوى الخاص بالصفحة -->  
      @yield('content')
      
    </div>
  </div>
</body>
</html>
