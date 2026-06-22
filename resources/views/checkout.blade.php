@extends('layouts.app')

@section('title', 'Checkout - GameHub')

@section('content')
<div class="container">
  
  <h2 class="mb-4 text-light"><i class="bi bi-shield-lock me-2 text-steam-accent"></i>CHECKOUT</h2>

  <div class="row">
    <!-- Left Column: Checkout Form -->
    <div class="col-lg-7 mb-4">
      <div class="card-steam-static p-4 border border-secondary shadow-sm rounded">
        <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">BILLING INFORMATION</h4>
        
        <form action="{{ route('checkout') }}" method="POST">
          @csrf
          <!-- Simulated Payment Mode -->
          <div class="mb-3">
            <label class="form-label text-muted small fw-bold">PAYMENT METHOD</label>
            <div class="d-flex gap-3 mb-3">
              <div class="form-check bg-dark p-3 rounded border border-secondary flex-grow-1">
                <input class="form-check-input ms-1 me-2" type="radio" name="payment_method" id="pay_wallet" value="wallet" checked>
                <label class="form-check-label text-light ms-4" for="pay_wallet">
                  <i class="bi bi-wallet2 me-1 text-steam-accent"></i> Steam Wallet (Simulated)
                </label>
              </div>
              <div class="form-check bg-dark p-3 rounded border border-secondary flex-grow-1">
                <input class="form-check-input ms-1 me-2" type="radio" name="payment_method" id="pay_card" value="card">
                <label class="form-check-label text-light ms-4" for="pay_card">
                  <i class="bi bi-credit-card me-1 text-warning"></i> Credit Card (Simulated)
                </label>
              </div>
            </div>
          </div>

          <!-- Billing Details -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="first_name" class="form-label text-muted small fw-bold">FIRST NAME</label>
              <input type="text" class="form-control form-control-steam" id="first_name" value="{{ Auth::user()->username }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="last_name" class="form-label text-muted small fw-bold">LAST NAME</label>
              <input type="text" class="form-control form-control-steam" id="last_name" placeholder="Optional">
            </div>
          </div>

          <div class="mb-3">
            <label for="billing_email" class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
            <input type="email" class="form-control form-control-steam" id="billing_email" value="{{ Auth::user()->email }}" required>
          </div>

          <div class="mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="termsCheck" required checked>
              <label class="form-check-label text-muted small" for="termsCheck">
                I agree to the terms of the GameHub Subscriber Agreement (last updated 15 June, 2026).
              </label>
            </div>
          </div>

          <!-- Submit CTA -->
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-steam-success py-2 fs-5"><i class="bi bi-wallet-fill me-2"></i>Complete Purchase</button>
            <a href="{{ route('cart') }}" class="btn btn-steam-dark"><i class="bi bi-x-circle me-1"></i>Cancel & Return</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Column: Cart items review -->
    <div class="col-lg-5">
      <div class="card-steam-static p-4 border border-secondary shadow-sm rounded">
        <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">REVIEW YOUR ITEMS</h4>
        
        <div class="mb-4" style="max-height: 300px; overflow-y: auto;">
          @foreach($cartItems as $item)
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary">
              <div class="d-flex align-items-center">
                <img src="{{ $item->game->image }}" alt="{{ $item->game->title }}" class="rounded me-2" style="width: 80px; height: 50px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
                <div>
                  <strong class="text-light d-block" style="font-size: 0.95rem;">{{ $item->game->title }}</strong>
                  <span class="text-muted small">{{ $item->game->category->name }}</span>
                </div>
              </div>
              <strong class="text-light">
                @if($item->game->is_discounted)
                  Rp {{ number_format($item->game->final_price, 0, ',', '.') }}
                @else
                  {{ $item->game->price == 0 ? 'Free' : 'Rp ' . number_format($item->game->price, 0, ',', '.') }}
                @endif
              </strong>
            </div>
          @endforeach
        </div>

        <div class="d-flex justify-content-between mb-3" style="font-size: 1.1rem;">
          <span class="text-muted">Total Price:</span>
          <strong class="text-steam-accent">Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
        </div>

        <div class="bg-dark p-3 rounded border border-secondary text-muted small">
          <i class="bi bi-shield-fill-check me-1 text-success"></i>
          Secure transaction: GameHub transactions are protected via simulated SSL key hashing. Purchases are added immediately to your Library.
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
