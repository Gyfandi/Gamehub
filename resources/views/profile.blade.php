@extends('layouts.app')

@section('title', 'My Profile - GameHub')

@section('content')
<div class="container">
  
  <h2 class="mb-4 text-light"><i class="bi bi-person-circle me-2 text-steam-accent"></i>ACCOUNT SETTINGS</h2>

  <!-- Stats Grid -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card-steam-static text-center p-3 border border-secondary rounded">
        <h5 class="text-muted small fw-bold">GAMES OWNED</h5>
        <span class="fs-2 text-light fw-bold">{{ $libraryCount }}</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-steam-static text-center p-3 border border-secondary rounded">
        <h5 class="text-muted small fw-bold">WISHLIST ITEMS</h5>
        <span class="fs-2 text-steam-accent fw-bold">{{ $wishlistCount }}</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-steam-static text-center p-3 border border-secondary rounded">
        <h5 class="text-muted small fw-bold">REVIEWS POSTED</h5>
        <span class="fs-2 text-success fw-bold">{{ $reviewCount }}</span>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Left Column: Edit Profile Form -->
    <div class="col-lg-5 mb-4">
      <div class="card-steam-static p-4 border border-secondary shadow-sm rounded">
        <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">PROFILE DETAILS</h4>
        
        <form action="{{ route('profile.update') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="username" class="form-label text-muted small fw-bold">USERNAME</label>
            <input type="text" name="username" id="username" class="form-control form-control-steam" value="{{ $user->username }}" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
            <input type="email" name="email" id="email" class="form-control form-control-steam" value="{{ $user->email }}" required>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label text-muted small fw-bold">NEW PASSWORD (LEAVE BLANK TO KEEP CURRENT)</label>
            <input type="password" name="password" id="password" class="form-control form-control-steam" placeholder="••••••••">
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-steam-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Column: Transaction History -->
    <div class="col-lg-7">
      <div class="card-steam-static p-4 border border-secondary shadow-sm rounded">
        <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">PURCHASE HISTORY</h4>
        
        @if(count($recentTransactions) > 0)
          <div class="table-responsive">
            <table class="table table-steam align-middle mb-0">
              <thead>
                <tr>
                  <th scope="col">Order ID</th>
                  <th scope="col">Date</th>
                  <th scope="col" class="text-end">Total Price</th>
                  <th scope="col" class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentTransactions as $trans)
                  <tr>
                    <td><strong class="text-light">#{{ $trans->id }}</strong></td>
                    <td>{{ $trans->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-end fw-bold text-light">Rp {{ number_format($trans->total, 0, ',', '.') }}</td>
                    <td class="text-center">
                      <span class="badge bg-opacity-25 bg-success text-success border border-success px-2 py-1">
                        Completed
                      </span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-5 text-muted">
            <i class="bi bi-receipt fs-1 d-block mb-3"></i>
            No purchases made yet.
          </div>
        @endif
      </div>
    </div>
  </div>

</div>
@endsection
