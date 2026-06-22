<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="dark">
  <title>@yield('title', 'Admin Dashboard - GameHub')</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Custom Steam-inspired CSS -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @yield('styles')
</head>
<body>

  <!-- Top Navbar for Admin -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-steam">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-shield-lock me-2 text-warning"></i>
        <span>GAME<span class="text-warning">HUB</span> <span class="badge bg-warning text-dark fs-7 ms-2">ADMIN</span></span>
      </a>
      
      <div class="d-flex align-items-center ms-auto">
        <span class="text-muted me-3 d-none d-md-inline">Logged in as: <strong class="text-light">{{ Auth::user()->username }}</strong></span>
        <a href="{{ route('landing') }}" class="btn btn-steam-dark btn-sm me-2"><i class="bi bi-shop me-1"></i>View Store</a>
        <a href="{{ route('auth.logout') }}" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </nav>

  <!-- Sidebar + Content Layout -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar Column -->
      <nav class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse collapse-horizontal show" id="sidebarMenu">
        <div class="position-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="admin-nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="admin-nav-link {{ Route::is('admin.games*') ? 'active' : '' }}" href="{{ route('admin.games') }}">
                <i class="bi bi-controller"></i>
                Games
              </a>
            </li>
            <li class="nav-item">
              <a class="admin-nav-link {{ Route::is('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                <i class="bi bi-tags"></i>
                Categories
              </a>
            </li>
            <li class="nav-item">
              <a class="admin-nav-link {{ Route::is('admin.publishers*') ? 'active' : '' }}" href="{{ route('admin.publishers') }}">
                <i class="bi bi-building"></i>
                Publishers
              </a>
            </li>
            <li class="nav-item">
              <a class="admin-nav-link {{ Route::is('admin.discounts*') ? 'active' : '' }}" href="{{ route('admin.discounts') }}">
                <i class="bi bi-percent"></i>
                Discounts
              </a>
            </li>
            <li class="nav-item">
              <a class="admin-nav-link {{ Route::is('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                <i class="bi bi-people"></i>
                Users
              </a>
            </li>
            <li class="nav-item">
              <a class="admin-nav-link {{ Route::is('admin.transactions*') ? 'active' : '' }}" href="{{ route('admin.transactions') }}">
                <i class="bi bi-receipt"></i>
                Transactions
              </a>
            </li>
          </ul>
          
          <hr class="border-secondary mt-4">
          <div class="px-3">
            <div class="text-muted" style="font-size: 0.8rem;">
              <strong>Production Mode</strong><br>
              Database: Connected.
            </div>
          </div>
        </div>
      </nav>

      <!-- Main Content Column -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <!-- Flash Alerts inside Content -->
        @if(session('success'))
          <div class="alert alert-success bg-opacity-25 bg-success border-success text-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger bg-opacity-25 bg-danger border-danger text-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
</body>
</html>
