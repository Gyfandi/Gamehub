@extends('layouts.app')

@section('title', 'Shopping Cart - GameHub')

@section('content')
<div class="container">
  
  <h2 class="mb-4 text-light"><i class="bi bi-cart3 me-2 text-steam-accent"></i>YOUR SHOPPING CART</h2>

  <div class="row">
    @if(count($cartItems) > 0)
      <!-- Left Column: Cart items table -->
      <div class="col-lg-8 mb-4">
        <div class="card-steam-static p-0 overflow-hidden border border-secondary shadow-sm rounded">
          <table class="table table-steam mb-0 align-middle">
            <thead>
              <tr>
                <th scope="col" style="width: 120px;">Game</th>
                <th scope="col">Title</th>
                <th scope="col" class="text-end" style="width: 150px;">Price</th>
                <th scope="col" class="text-center" style="width: 100px;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cartItems as $item)
                <tr>
                  <td>
                    <img src="{{ $item->game->image }}" alt="{{ $item->game->title }}" class="rounded" style="width: 100px; height: 60px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
                  </td>
                  <td>
                    <a href="{{ route('detail', $item->game->id) }}" class="fw-bold text-light">{{ $item->game->title }}</a>
                    <span class="d-block small text-muted">{{ $item->game->category->name }}</span>
                  </td>
                  <td class="text-end">
                    @if($item->game->is_discounted)
                      <span class="badge bg-steam-discount me-2">-{{ $item->game->activeDiscount->percentage }}%</span>
                      <span class="text-muted text-decoration-line-through small me-1">Rp {{ number_format($item->game->price, 0, ',', '.') }}</span>
                      <strong class="text-steam-success">Rp {{ number_format($item->game->final_price, 0, ',', '.') }}</strong>
                    @else
                      <strong class="text-light">{{ $item->game->price == 0 ? 'Free' : 'Rp ' . number_format($item->game->price, 0, ',', '.') }}</strong>
                    @endif
                  </td>
                  <td class="text-center">
                    <form action="{{ route('cart.remove', $item->game->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-outline-danger btn-sm border-0"><i class="bi bi-trash fs-5"></i></button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          <a href="{{ route('catalog') }}" class="btn btn-steam-dark"><i class="bi bi-arrow-left me-2"></i>Continue Shopping</a>
        </div>
      </div>

      <!-- Right Column: Summary panel -->
      <div class="col-lg-4">
        <div class="card-steam-static p-4 border border-secondary shadow-sm rounded">
          <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">ORDER SUMMARY</h4>
          
          <div class="d-flex justify-content-between mb-3" style="font-size: 1.05rem;">
            <span class="text-muted">Subtotal:</span>
            <span class="text-light">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
          </div>

          <div class="d-flex justify-content-between mb-3 border-bottom border-secondary pb-3" style="font-size: 1.05rem;">
            <span class="text-muted">Estimated Tax:</span>
            <span class="text-success fw-bold">FREE</span>
          </div>

          <div class="d-flex justify-content-between mb-4" style="font-size: 1.25rem;">
            <strong class="text-light">Total Price:</strong>
            <strong class="text-steam-accent">Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
          </div>

          <div class="d-grid">
            <a href="{{ route('checkout.view') }}" class="btn btn-steam-success py-2 fs-5"><i class="bi bi-shield-check me-2"></i>Proceed to Checkout</a>
          </div>
        </div>
      </div>
    @else
      <!-- Empty Cart Screen -->
      <div class="col-12 text-center py-5">
        <i class="bi bi-cart-x fs-1 text-muted d-block mb-3"></i>
        <h3 class="text-muted">Your cart is currently empty</h3>
        <p class="text-muted">Before checking out, you must add some games to your cart.</p>
        <a href="{{ route('catalog') }}" class="btn btn-steam-primary mt-3 px-4 py-2"><i class="bi bi-shop me-2"></i>Browse Store</a>
      </div>
    @endif
  </div>

</div>
@endsection
