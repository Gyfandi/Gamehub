@extends('layouts.app')

@section('title', 'My Wishlist - GameHub')

@section('content')
<div class="container">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-light"><i class="bi bi-heart-fill me-2 text-danger"></i>MY WISHLIST</h2>
    <span class="text-muted">{{ count($wishlisted) }} games wishlisted</span>
  </div>

  <div class="row">
    @if(count($wishlisted) > 0)
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($wishlisted as $item)
          @php $game = $item->game; @endphp
          @if($game)
            <div class="col" id="wishlist-item-{{ $item->id }}">
              <div class="card card-steam h-100 border border-secondary shadow-sm hover-scale">
                <div class="position-relative">
                  <img src="{{ $game->image }}" class="card-img-top" alt="{{ $game->title }}" style="height: 180px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
                  
                  @if($game->is_discounted)
                    <span class="position-absolute top-0 start-0 bg-success text-dark font-weight-bold px-2 py-1 m-2 rounded shadow-sm" style="font-size: 0.8rem; font-weight: 800;">
                      -{{ $game->activeDiscount->percentage }}%
                    </span>
                  @endif

                  <span class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 px-2 py-1 m-2 rounded text-light fw-bold" style="font-size: 0.85rem;">
                    @if($game->is_discounted)
                      <span class="text-decoration-line-through text-muted me-1" style="font-size: 0.75rem;">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                      <span class="text-steam-success">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                    @else
                      {{ $game->price == 0 ? 'Free to Play' : 'Rp ' . number_format($game->price, 0, ',', '.') }}
                    @endif
                  </span>
                </div>
                <div class="card-body d-flex flex-column justify-content-between bg-dark bg-opacity-25">
                  <div>
                    <h4 class="card-title text-truncate text-light mb-1 fs-5">{{ $game->title }}</h4>
                    <p class="text-muted small mb-2">{{ $game->publisher->name }}</p>
                    <span class="badge badge-steam-category mb-3">{{ $game->category->name }}</span>
                  </div>

                  <div class="mt-auto pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                      @csrf
                      <input type="hidden" name="game_id" value="{{ $game->id }}">
                      <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-heart-break me-1"></i>Remove</button>
                    </form>

                    <div class="d-flex gap-1">
                      <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->id }}">
                        <button type="submit" class="btn btn-steam-success btn-sm px-2" title="Add to Cart"><i class="bi bi-cart-plus me-1"></i>Cart</button>
                      </form>
                      <a href="{{ route('detail', $game->id) }}" class="btn btn-steam-primary btn-sm px-3">View</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>
    @else
      <div class="col-12 text-center py-5">
        <i class="bi bi-heart fs-1 text-muted d-block mb-3"></i>
        <h3 class="text-muted">Your Wishlist is empty</h3>
        <p class="text-muted">Browse the catalog to add games you are interested in!</p>
        <a href="{{ route('catalog') }}" class="btn btn-steam-primary mt-3 px-4 py-2"><i class="bi bi-shop me-2"></i>Go to Store</a>
      </div>
    @endif
  </div>

</div>
@endsection
