<header class="header">   
  <nav class="navbar navbar-expand-lg">
    </div>
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="navbar-header">
        <!-- Navbar Header--><a href="index.html" class="navbar-brand">
          <div class="brand-text brand-big visible text-uppercase">
            <strong style="color: #0088ff !important;">Learn</strong><strong>Pro</strong>
        </div>
        
          <div class="brand-text brand-sm"><strong class="text-primary">L</strong><strong>A</strong></div></a>
        <!-- Sidebar Toggle Btn-->
        <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
      </div>
      
     <!-- Log out -->
<div class="list-inline-item logout">
  <button type="button" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</button>
</div>

<form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
  @csrf
</form>

    </div>
  </nav>
</header>