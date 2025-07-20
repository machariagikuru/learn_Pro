<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'LearnPro | Dashboard')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Bootstrap -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
      crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Boxicons -->
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<!-- Custom Styles -->
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/preloader.css') }}">

<!-- Stripe Checkout -->
<script src="https://checkout.stripe.com/checkout.js"></script>

<!-- Favicon -->
<link rel="icon" href="{{ asset('Photos/logomark.svg') }}">

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
        <a href="{{ route('profile.edit') }}" class="block p-3 bg-blue-50 text-blue-600 font-medium rounded-lg">
          <i class='bx bxs-cog mr-2'></i> Settings
        </a>
        <form action="{{ route('request_instructor_access') }}" method="POST">
          @csrf
          <button type="submit" class="simple-btn">
              <i class="fas fa-chalkboard-teacher"></i>
              Request Instructor Role
          </button>
      </form>
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
    <div class="content p-4 flex-grow-1">
      <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="fs-1 fw-bold">
            Hello, <span class="text-primary">{{ Auth::user()->first_name }}</span> 👋
          </h4>
          <h6 class="text-secondary">Let's learn something new today!</h6>
        </div>
        <div class="d-flex align-items-center gap-4">
            <div class="d-flex position-relative">
                <input type="text" id="liveSearchInput" class="form-control me-2" placeholder="Search from courses..." autocomplete="off">
                <div id="liveSearchResults" class="dropdown-menu position-absolute w-100" style="display: none; top: 100%; z-index: 1050;">
                    <!-- Search results will be appended here -->
                </div>
            </div>
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
                      @if(isset($notification->data['message']))
                        <div class="small text-muted">{{ $notification->data['message'] }}</div>
                      @endif
                      @php $status = $notification->data['status'] ?? null; @endphp
                      @if($status == 'approved')
                        <span class="badge bg-success ms-2">Approved</span>
                      @elseif($status == 'rejected')
                        <span class="badge bg-danger ms-2">Rejected</span>
                      @elseif($status == 'pending')
                        <span class="badge bg-warning text-dark ms-2">Pending</span>
                      @elseif($status)
                        <span class="badge bg-secondary ms-2">{{ ucfirst($status) }}</span>
                      @endif
                    </div>
                    <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST" class="d-inline">
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
      </header>
      <hr>
      @yield('content')
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @yield('scripts')
  <!-- Bootstrap JS (for dropdowns) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('liveSearchInput');
        const resultsContainer = document.getElementById('liveSearchResults');
        let debounceTimer;

        searchInput.addEventListener('keyup', function () {
            clearTimeout(debounceTimer);
            const query = searchInput.value;

            if (query.length < 2) {
                resultsContainer.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/live-search-courses?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(result => {
                                const link = document.createElement('a');
                                link.href = result.url;
                                link.classList.add('dropdown-item');
                                link.innerHTML = `<div>${result.title}</div><small class="text-muted">${result.course_title}</small>`;
                                resultsContainer.appendChild(link);
                            });
                            resultsContainer.style.display = 'block';
                        } else {
                            const noResult = document.createElement('span');
                            noResult.classList.add('dropdown-item', 'text-muted');
                            noResult.innerText = 'No results found';
                            resultsContainer.appendChild(noResult);
                            resultsContainer.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resultsContainer.style.display = 'none';
                    });
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function (event) {
            if (!searchInput.contains(event.target)) {
                resultsContainer.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>
