<div class="d-flex align-items-stretch">
  <!-- Sidebar Navigation-->
  <nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
      <div class="avatar">
        <img src="{{ asset('Photos/logomark.svg') }}" alt="..." class="img-fluid rounded-circle">
      </div>
      <div class="title">
        <h1 class="h6">Instructor Tools</h1>
        <p>Hello , {{ Auth::user()->first_name }}</p>

      </div>
    </div>
    <!-- Sidebar Navigation Menus -->
    <span class="heading">Main</span>
    <ul class="list-unstyled" id="sidebarAccordion">
      <li class="">
        <a href="{{ url('instructor/dashboard') }}">
          <i class="icon-home"></i> Home
        </a>
      </li>
      <li>
        <a href="{{ url('view_category') }}">
          <i class="icon-grid"></i> Course Category
        </a>
      </li>
      <li>
        <a href="#coursesDropdown" aria-expanded="false" data-toggle="collapse">
          <i class="icon-windows"></i> Courses
        </a>
        <ul id="coursesDropdown" class="collapse list-unstyled" data-parent="#sidebarAccordion">
          <li><a href="{{ url('add_course') }}">Add Course</a></li>
          <li><a href="{{ url('view_course') }}">View Course</a></li>
        </ul>
      </li>
      <li>
        <a href="#ChaptersDropdown" aria-expanded="false" data-toggle="collapse">
          <i class="icon-windows"></i> Chapters
        </a>
        <ul id="ChaptersDropdown" class="collapse list-unstyled" data-parent="#sidebarAccordion">
          <li><a href="{{ route('show.add.chapter') }}">Add Chapter</a></li>
          <li><a href="{{ route('view.chapters') }}">View Chapter</a></li>
        </ul>
      </li>
      <li>
        <a href="#LessonsDropdown" aria-expanded="false" data-toggle="collapse">
          <i class="icon-windows"></i> Lessons
        </a>
        <ul id="LessonsDropdown" class="collapse list-unstyled" data-parent="#sidebarAccordion">
          <li><a href="{{ route('add.lesson') }}">Add Lesson</a></li>
          <li><a href="{{ route('view.lessons') }}">View Lessons</a></li>          
        </ul>
      </li>
    </ul>
  </nav>