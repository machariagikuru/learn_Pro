<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Instructor | Dashboard')</title>
  <link rel="icon" href="{{ asset('Photos/logomark.svg') }}">
  
  <!-- Stylesheets -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    /* Global Styles */
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f6f9;
    }
    
    /* Sidebar Styles */
    .sidebar {
      width: 18rem;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: #fff;
      border-right: 1px solid #e0e0e0;
      transition: width 0.3s;
    }
    .sidebar:hover {
      width: 20rem;
    }
    .sidebar .logo-section {
      padding: 1.5rem;
      border-bottom: 1px solid #e0e0e0;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .sidebar-nav {
      padding: 1rem;
    }
    .sidebar-nav .nav-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .nav-link {
      color: #555;
      border-radius: 0.5rem;
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      margin-bottom: 0.5rem;
      transition: background-color 0.3s, color 0.3s;
    }
    .nav-link:hover, .nav-link.active {
      background-color: #e9f1ff;
      color: #007bff;
    }
    .nav-link i {
      margin-right: 0.75rem;
      width: 1.5rem;
      text-align: center;
    }
    .dropdown-toggle .fa-chevron-down {
      transition: transform 0.3s;
      margin-left: auto;
    }
    .dropdown-toggle[aria-expanded="true"] .fa-chevron-down {
      transform: rotate(180deg);
    }
    .submenu {
      list-style: none;
      padding-left: 1rem;
      margin-top: 0.5rem;
    }
    
    /* Content Styles */
    .content {
      margin-left: 18rem;
      padding: 1.5rem;
      transition: margin-left 0.3s;
      width: calc(100% - 18rem);
    }
    
    /* Header Styles */
    .header {
      background: #fff;
      padding: 1rem 2rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 500;
    }
    
    /* Custom logout button */
    .logout-button {
      border: none;
      background: transparent;
      color: #555;
      cursor: pointer;
      font-size: 16px;
    }
    .logout-button:hover {
      color: #000;
    }
    .h1-basic {
      font-size: 1.5rem;
      font-weight: 700;
      color: #007bff;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar">
      <a href="#" class="logo-section text-decoration-none">
        <img src="{{ asset('Photos/logomark.svg') }}" alt="LearnPro Logo">
        <h1 class="h1-basic">LearnPro</h1>
      </a>
      
      <nav class="sidebar-nav">
        <!-- Header with Instructor Tools and Notifications Bell -->
        <div class="nav-header mb-2">
          <h4>Instructor Tools</h4>
          <!-- Notifications Bell inside the header -->
          <div class="dropdown">
            <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-bell"></i>
              @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  {{ auth()->user()->unreadNotifications->count() }}
                  <span class="visually-hidden">unread notifications</span>
                </span>
              @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-2" style="width: 300px;">
              <h6 class="dropdown-header">Notifications</h6>
              @forelse(auth()->user()->unreadNotifications as $notification)
                <li class="mb-2">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>
                      @php
                        $status = $notification->data['status'] ?? null;
                        $studentName = $notification->data['student_name'] ?? null;
                      @endphp
                      @if($status)
                        @if($status == 'approved')
                          <span class="badge bg-success ms-2">Approved</span>
                        @elseif($status == 'rejected')
                          <span class="badge bg-danger ms-2">Rejected</span>
                        @elseif($status == 'pending')
                          <span class="badge bg-warning text-dark ms-2">Pending</span>
                        @else
                          <span class="badge bg-secondary ms-2">{{ ucfirst($status) }}</span>
                        @endif
                      @elseif($studentName)
                        <span class="badge bg-info ms-2">{{ $studentName }}</span>
                      @endif
                    </div>
                    <form action="{{ route('instructor.notifications.markRead', $notification->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-primary">Read</button>
                  </form>
                  
                  </div>
                </li>
              @empty
                <li>
                  <small class="text-muted">No new notifications</small>
                </li>
              @endforelse
            </ul>
          </div>
        </div>
        <h6>Hello, <span class="text-primary">{{ Auth::user()->first_name }}</span> 👋</h6>
        <br>
        <ul class="list-unstyled">
          <li><a href="{{ url('instructor/dashboard') }}" class="nav-link"><i class="fas fa-home"></i> Home</a></li>
          <li><a href="{{ route('instructor.profile.edit') }}" class="nav-link"><i class="fas fa-user-cog"></i> Settings</a></li>
          <li>
            <a href="{{ route('instructor.notifications') }}" class="nav-link">
              <i class="fas fa-user-cog"></i> Messages
            </a>
          </li>
          

          <!-- Logout -->
          <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="nav-link w-100 text-start logout-button">
                <i class="fas fa-sign-out-alt"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </nav>
    </aside>
    
    <!-- Main Content -->
    <div class="content">
      <main>
        @yield('body')
        @yield('scripts')
        @yield('content')
      </main>
    </div>
  </div>
  
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
