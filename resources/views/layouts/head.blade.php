<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>@yield('title', 'LearnPro')</title>

  <!-- Bootstrap & Font Awesome CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> {{-- Use asset() helper --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}"> {{-- Use asset() helper --}}
  <link rel="stylesheet" href="{{ asset('assets/css/course-enroll.css') }}"> {{-- Use asset() helper --}}
  <link rel="stylesheet" href="{{ asset('assets/css/preloader.css') }}"> {{-- Use asset() helper --}}
  <link rel="icon" href="{{ asset('Photos/logomark.svg') }}">

  <style>
    /* Awesome Preloader Styles (Keep existing styles) */
    #preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      transition: opacity 0.5s ease-out;
    }
    .loader {
      border: 8px solid #f3f3f3;
      border-top: 8px solid #3498db;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    #preloader p {
      margin-top: 15px;
      font-size: 1.2rem;
      font-weight: bold;
      color: #555;
    }

    /* Basic styling for search results dropdown */
    #search-results-container {
        position: relative; /* Needed for absolute positioning of results */
    }
    #search-results {
        position: absolute;
        top: 100%; /* Position below the search input/button area */
        right: 0; /* Align to the right */
        width: 300px; /* Adjust width as needed */
        max-height: 400px; /* Limit height and enable scrolling */
        overflow-y: auto;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1050; /* Ensure it appears above other elements */
        margin-top: 0.5rem; /* Space below the search bar */
        display: none; /* Hide initially */
    }
    #search-results .list-group-item {
        border: none; /* Remove default list item borders */
        border-bottom: 1px solid #eee; /* Add subtle separators */
    }
     #search-results .list-group-item:last-child {
        border-bottom: none; /* Remove border from last item */
    }
    #search-results a {
        text-decoration: none;
        color: #333;
        display: block; /* Make the whole item clickable */
        padding: 0.75rem 1.25rem;
    }
     #search-results a:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
     }
     #search-results .text-muted {
        font-size: 0.85em;
     }

    .challenge-modal-content {
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(44, 62, 80, 0.18);
      border: none;
      overflow: hidden;
    }
    .challenge-header {
      color: #2d7be5;
      font-size: 1.15rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      display: block;
      margin-top: 10px;
    }
    .challenge-title {
      font-size: 2.2rem;
      font-weight: 700;
      color: #222;
      font-family: 'Segoe UI', Arial, sans-serif;
      letter-spacing: 1px;
    }
    .challenge-desc a {
      color: #2d7be5;
      text-decoration: underline;
    }
    .challenge-upload-box {
      border: 2px solid #e3eafc;
      border-radius: 12px;
      background: #f8faff;
      padding: 18px 12px 22px 12px;
      margin: 0 auto;
      max-width: 420px;
    }
    .upload-box-inner {
      width: 100%;
    }
    .upload-drag-area {
      border: 2px dashed #b6d0fa;
      border-radius: 10px;
      background: #f4f8ff;
      padding: 28px 10px 18px 10px;
      cursor: pointer;
      transition: border-color 0.2s;
    }
    .upload-drag-area:hover {
      border-color: #2d7be5;
    }
    .upload-icon {
      margin-bottom: 8px;
    }
    .challenge-upload-btn {
      background: #2d7be5;
      border: none;
      border-radius: 8px;
      margin-top: 10px;
    }
    .challenge-upload-btn:hover {
      background: #1a5bb8;
    }
  </style>
</head>
<body>
  <!-- Preloader (Keep existing) -->
  <div id="preloader">
    <div class="loader"></div>
    <p>Loading, please wait...</p>
  </div>

  <!-- Header (Keep existing structure) -->
  <header class="d-flex justify-content-between align-items-center p-3 bg-white shadow-sm">
    <div class="d-flex align-items-center">
      <a href="javascript:history.back()" class="btn btn-light me-3">
        <i class="fas fa-arrow-left"></i>
      </a>
      <a href="{{ route('dashboard') }}" class="text-decoration-none" style="display:inline-flex;">
        <img src="{{ asset('Photos/logomark.svg') }}" alt="logo" id="logo">
        <h2 class="text-primary fw-bold">LearnPro</h2>
      </a>
    </div>
    <div class="d-flex align-items-center gap-3">
      <!-- Dropdown (Keep existing) -->
      <div class="dropdown">
        <!-- ... dropdown content ... -->
         <a href="javascript:void(0)"
           class="text-primary text-decoration-none fw-semibold dropdown-toggle"
           id="dropdownMenuLink"
           data-bs-toggle="dropdown"
           aria-expanded="false">
          {{ Auth::user()->first_name }}
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
              <i class="fa-solid fa-gear"></i> Settings
            </a>
          </li>
          <li>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>

     <!-- Search Area -->
      <div id="search-results-container"> {{-- Wrap search input/button and results --}}
        <div class="d-flex">
          <input type="text" id="search-query" class="form-control me-2" placeholder="Search lessons...">
          {{-- Changed button type to button to prevent form submission --}}
          <button type="button" id="search-button" style="background-color: rgba(158, 158, 158, 0.769);" class="btn btn-secondary px-2 rounded-5">
            <i class="fas fa-search"></i>
          </button>
        </div>

        <!-- Container to display search results -->
        <div id="search-results" class="list-group shadow-sm"> {{-- Add list-group class for styling --}}
          {{-- Results will be populated here by JavaScript --}}
        </div>
      </div>


      <!-- Notification Bell -->
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

    <!-- Daily Challenge Banner -->
    @if(auth()->check() && isset($course) && auth()->user()->courses->contains($course->id))
    <div class="text-center bg-primary text-white py-3 mb-4">
      <h5 id="daily-challenge-btn" style="cursor:pointer; text-decoration:underline;">Claim Your Daily Challenge ✨</h5>
    </div>
    @endif

  @yield('content')

  <!-- Bootstrap JS (includes Popper) -->
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script> {{-- Use asset() helper --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <script>
    // Fade out the preloader when the page loads (Keep existing)
    window.onload = function() {
      const preloader = document.getElementById("preloader");
      if (preloader) { // Check if preloader exists before manipulating
        preloader.style.opacity = "0";
        setTimeout(() => {
          preloader.style.display = "none";
        }, 500);
      }
    };

    // --- NEW SEARCH JAVASCRIPT ---
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('search-query');
      const searchButton = document.getElementById('search-button');
      const resultsContainer = document.getElementById('search-results');
      const searchResultsContainer = document.getElementById('search-results-container'); // Parent container

      // Function to perform search
      const performSearch = async () => {
        const query = searchInput.value.trim();

        if (query.length < 2) { // Optional: Don't search for very short queries
          resultsContainer.innerHTML = '';
          resultsContainer.style.display = 'none'; // Hide results container
          return;
        }

        try {
          // Use the named route for the search URL
          const searchUrl = `{{ route('lessons.search') }}?query=${encodeURIComponent(query)}`;
          const response = await fetch(searchUrl, {
            method: 'GET',
            headers: {
              'Accept': 'application/json', // Expect JSON response
              'X-Requested-With': 'XMLHttpRequest' // Often needed by Laravel
            }
          });

          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const results = await response.json();

          // Clear previous results
          resultsContainer.innerHTML = '';

          if (results.length > 0) {
            results.forEach(lesson => {
              const listItem = document.createElement('a');
              listItem.href = lesson.url; // Use the URL from the backend
              listItem.classList.add('list-group-item', 'list-group-item-action'); // Add Bootstrap classes
              listItem.innerHTML = `
                <div>${lesson.description}</div>
                <div class="text-muted small">${lesson.course_title || ''}</div> 
              `; // Display lesson title and course title
              resultsContainer.appendChild(listItem);
            });
            resultsContainer.style.display = 'block'; // Show results container
          } else {
            resultsContainer.innerHTML = '<div class="p-3 text-muted">No lessons found.</div>';
            resultsContainer.style.display = 'block'; // Show "No results" message
          }
        } catch (error) {
          console.error("Search failed:", error);
          resultsContainer.innerHTML = '<div class="p-3 text-danger">Search failed. Please try again.</div>';
          resultsContainer.style.display = 'block'; // Show error message
        }
      };

      // Trigger search on button click
      if (searchButton) {
        searchButton.addEventListener('click', performSearch);
      }

      // Optional: Trigger search as user types (with debounce)
      let debounceTimer;
      if (searchInput) {
        searchInput.addEventListener('keyup', () => {
          clearTimeout(debounceTimer);
          debounceTimer = setTimeout(performSearch, 300); // Wait 300ms after typing stops
        });
      }

       // Hide results when clicking outside the search area
        document.addEventListener('click', function(event) {
            if (searchResultsContainer && !searchResultsContainer.contains(event.target)) {
                resultsContainer.style.display = 'none';
            }
        });

         // Prevent hiding when clicking inside the input or results
        if (searchInput) {
            searchInput.addEventListener('focus', () => {
                // Optionally re-show results or perform search if query exists
                if (searchInput.value.trim().length > 0 && resultsContainer.children.length > 0) {
                    resultsContainer.style.display = 'block';
                }
            });
        }

    });
    // --- END OF SEARCH JAVASCRIPT ---

    // Daily Challenge Modal Trigger
    document.addEventListener('DOMContentLoaded', function() {
      var dailyBtn = document.getElementById('daily-challenge-btn');
      if (dailyBtn) {
        dailyBtn.addEventListener('click', function() {
          var modal = new bootstrap.Modal(document.getElementById('dailyChallengeModal'));
          modal.show();
        });
      }
    });

    // Drag & Drop and Browse logic for the upload box
    (function() {
      var dragArea = document.querySelector('.upload-drag-area');
      var fileInput = document.getElementById('challenge-file-input');
      var browseLink = document.querySelector('.upload-browse');
      if (dragArea && fileInput && browseLink) {
        dragArea.addEventListener('click', function() { fileInput.click(); });
        browseLink.addEventListener('click', function(e) { e.stopPropagation(); fileInput.click(); });
        dragArea.addEventListener('dragover', function(e) { e.preventDefault(); dragArea.style.borderColor = '#2d7be5'; });
        dragArea.addEventListener('dragleave', function(e) { dragArea.style.borderColor = '#b6d0fa'; });
        dragArea.addEventListener('drop', function(e) { e.preventDefault(); dragArea.style.borderColor = '#b6d0fa'; fileInput.files = e.dataTransfer.files; });
      }
    })();

  </script>

  @include('components.daily-challenge-modal')
</body>
</html>