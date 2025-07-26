<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin | Dashboard</title>
    <link rel="icon" href="{{ asset('Photos/logomark.svg') }}">
    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css">

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

        .sidebar .logo-section img {
            width: 2.5rem;
            height: 2.5rem;
        }

        .sidebar .logo-section h1 {
            font-size: 1.5rem;
            color: #333;
            margin: 0;
        }

        .sidebar-nav {
            padding: 1rem;
        }

        .sidebar-nav h4 {
            font-weight: 500;
            color: #555;
            margin-bottom: 1rem;
        }

        .nav-link {
            color: #555;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #e9f1ff;
            color: #007bff;
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 1.5rem;
            text-align: center;
        }

        .nav-link.dropdown-toggle::after {
            display: none !important;
            content: none !important;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 500;
        }

        .header h2 {
            margin: 0;
            font-size: 1.75rem;
            color: #333;
        }

        .header-actions a {
            margin-left: 1.5rem;
            color: #333;
            text-decoration: none;
            position: relative;
        }

        .notification-bell .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: #dc3545;
            color: #fff;
            font-size: 0.75rem;
            text-align: center;
            line-height: 18px;
        }
    </style>
</head>

<body>
    <!-- Split the page into two sections: Sidebar and Main Content -->
    <div class="d-flex">
        <!-- Sidebar Section -->
        <aside class="sidebar">
            <!-- Logo Section -->
            <a href="{{ route('dashboard') }}" class="logo-section text-decoration-none">
                <img src="{{ asset('Photos/logomark.svg') }}" alt="LearnPro Logo">
                <h1>LearnPro</h1>
            </a>
            <!-- Sidebar Navigation Menus -->
            <nav class="sidebar-nav">
                <!-- Header with Admin Panel and Bell Icon -->
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h4 class="mb-0">Admin Panel</h4>
                    <!-- Notifications Bell -->
                    <div class="dropdown">
                        <button class="btn btn-light position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="width: 300px;" aria-labelledby="notificationDropdown">
                            <h6 class="dropdown-header">Notifications</h6>
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <li class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>
                                                <h6>
                                                    @if ($notification->type == 'App\Notifications\InstructorAccessRequestNotification')
                                                        Instructor Access Request
                                                    @else
                                                        Notification
                                                    @endif
                                                </h6>
                                                {{ $notification->data['title'] ?? ($notification->data['message'] ?? 'No details available') }}
                                            </strong>
                                            @php $status = $notification->data['status'] ?? null; @endphp
                                            @if ($status == 'approved')
                                                <span class="badge bg-success ms-2">Approved</span>
                                            @elseif($status == 'rejected')
                                                <span class="badge bg-danger ms-2">Rejected</span>
                                            @elseif($status == 'pending')
                                                <span class="badge bg-warning text-dark ms-2">Pending</span>
                                            @elseif($status)
                                                <span class="badge bg-secondary ms-2">{{ ucfirst($status) }}</span>
                                            @endif
                                        </div>
                                        <form
                                            action="{{ route('instructor.notifications.markRead', $notification->id) }}"
                                            method="POST" class="d-inline">
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

                <h6>
                    Hello, <span class="text-primary">{{ Auth::user()->first_name }}</span> 👋
                </h6>
                <br><br>
                <ul class="list-unstyled" id="sidebarAccordion">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ route('admin.users') }}" class="nav-link">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ route('admin.pending_courses') }}" class="nav-link">
                            <i class="fas fa-users"></i> Manage Content
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pendingRequests') }}" class="nav-link">
                            <i class="fas fa-users"></i> Pending Requests
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.notifications') }}" class="nav-link">
                            <i class="fas fa-user-cog"></i> Messages
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.profile.edit') }}" class="nav-link">
                            <i class="fas fa-user-cog"></i> Settings
                        </a>
                    </li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-flex">
                            @csrf
                            <button type="submit" class="nav-link w-100 text-start"
                                style="border: none; background: none; color: #555;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Section -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize all dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>
</body>

</html>
