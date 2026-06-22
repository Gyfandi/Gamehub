<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="dark">
  <title>@yield('title', 'GameHub - Digital Game Marketplace')</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Custom Steam-inspired CSS -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @yield('styles')
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-steam">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
        <i class="bi bi-controller me-2 text-steam-accent"></i>
        <span>GAME<span class="text-steam-accent">HUB</span></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <!-- Left Nav Links -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link {{ Route::is('landing') ? 'active' : '' }}" href="{{ route('landing') }}">STORE</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Route::is('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">BROWSE</a>
          </li>
          @if(Auth::check())
            <li class="nav-item">
              <a class="nav-link {{ Route::is('library') ? 'active' : '' }}" href="{{ route('library') }}">LIBRARY</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('wishlist') ? 'active' : '' }}" href="{{ route('wishlist') }}">WISHLIST</a>
            </li>
            @if(Auth::user()->role === 'admin')
              <li class="nav-item">
                <a class="nav-link text-warning fw-bold {{ Request::is('admin*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">ADMIN</a>
              </li>
            @endif
          @endif
        </ul>

        <!-- Search Form in Navbar -->
        <form class="d-flex me-lg-3 mb-2 mb-lg-0 position-relative align-items-center" action="{{ route('catalog') }}" method="GET" style="max-width: 300px; width: 100%;">
          <input class="form-control form-control-steam pe-5" type="search" name="search" placeholder="search store" value="{{ request('search') }}" aria-label="Search">
          <button class="btn border-0 text-muted position-absolute end-0 top-50 translate-middle-y" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>

        <!-- Right User Actions -->
        <ul class="navbar-nav align-items-center">
          @if(Auth::check())
            <!-- Shopping Cart link -->
            <li class="nav-item me-3">
              <a class="nav-link d-flex align-items-center position-relative py-1" href="{{ route('cart') }}">
                <i class="bi bi-cart3 fs-5 me-1"></i>
                @php
                  $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
                  $cartCount = $cart ? \App\Models\CartItem::where('cart_id', $cart->id)->count() : 0;
                @endphp
                @if($cartCount > 0)
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-dark" style="font-size: 0.7rem;">
                    {{ $cartCount }}
                  </span>
                @endif
              </a>
            </li>

            <!-- User Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center text-steam-accent" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-5 me-2"></i>
                <span>{{ Auth::user()->username }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-secondary bg-dark" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('library') }}"><i class="bi bi-controller me-2"></i>My Library</a></li>
                <li><a class="dropdown-item" href="{{ route('wishlist') }}"><i class="bi bi-heart-fill me-2 text-danger"></i>My Wishlist</a></li>
                <li><hr class="dropdown-divider border-secondary"></li>
                <li>
                  <a class="dropdown-item text-danger" href="{{ route('auth.logout') }}">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                  </a>
                </li>
              </ul>
            </li>
          @else
            <li class="nav-item me-2">
              <a class="btn btn-steam-dark btn-sm py-1 px-3" href="{{ route('auth.login.view') }}">Login</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-steam-primary btn-sm py-1 px-3" href="{{ route('auth.register.view') }}">Sign Up</a>
            </li>
          @endif
        </ul>
      </div>
    </div>
  </nav>

  <!-- Flash Messages Banner -->
  <div class="container mt-3">
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
  </div>

  <!-- Main View Slot -->
  <main class="py-4">
    @yield('content')
  </main>

  <!-- Steam-inspired Footer -->
  <footer class="footer-steam text-center">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-4 text-md-start mb-3 mb-md-0">
          <div class="footer-logo">
            <i class="bi bi-controller me-2 text-steam-accent"></i>
            <span>GAME<span class="text-steam-accent">HUB</span></span>
          </div>
          <small class="d-block mt-1">&copy; 2026 GameHub Inc. All rights reserved.</small>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
          <small>
            A marketplace prototype integration with Oracle Database XE.
          </small>
        </div>
        <div class="col-md-4 text-md-end">
          <div class="d-flex justify-content-md-end justify-content-center gap-3">
            <a href="#" class="text-muted"><i class="bi bi-github fs-5"></i></a>
            <a href="#" class="text-muted"><i class="bi bi-discord fs-5"></i></a>
            <a href="#" class="text-muted"><i class="bi bi-twitter-x fs-5"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
</body>
</html>
