@extends('layouts.app')

@section('title', 'Browse Games - GameHub')

@section('content')
<div class="container">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-controller me-2 text-steam-accent"></i>BROWSE THE STORE</h2>
    <span class="text-muted">{{ count($games) }} games found</span>
  </div>

  <div class="row">
    <!-- Left Sidebar: Filters -->
    <div class="col-lg-3 mb-4">
      <div class="card-steam-static p-3 border border-secondary shadow-sm rounded">
        <h4 class="fs-5 border-bottom border-secondary pb-2 mb-3 text-light">FILTERS</h4>
        
        <form action="{{ route('catalog') }}" method="GET" id="filterForm">
          <!-- Live Search inside form -->
          <div class="mb-3">
            <label class="form-label text-muted small fw-bold">SEARCH BY NAME</label>
            <input type="text" name="search" class="form-control form-control-steam" placeholder="Game title, developer..." value="{{ request('search') }}">
          </div>

          <!-- Sorting -->
          <div class="mb-3">
            <label class="form-label text-muted small fw-bold">SORT BY</label>
            <select name="sort" class="form-select form-control-steam" onchange="document.getElementById('filterForm').submit()">
              <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Newest Releases</option>
              <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
              <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
              <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Top Rated</option>
            </select>
          </div>

          <!-- Categories Filter -->
          <div class="mb-4">
            <label class="form-label text-muted small fw-bold">CATEGORIES</label>
            <div style="max-height: 250px; overflow-y: auto; padding-right: 5px;" class="custom-scrollbar">
              @foreach($categories as $category)
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" name="category[]" value="{{ $category->id }}" id="cat_{{ $category->id }}"
                    {{ in_array($category->id, (array)request('category', [])) ? 'checked' : '' }}>
                  <label class="form-check-label text-light" style="font-size: 0.9rem; cursor: pointer;" for="cat_{{ $category->id }}">
                    {{ $category->name }}
                  </label>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Action buttons -->
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-steam-primary btn-sm"><i class="bi bi-funnel-fill me-1"></i>Apply Filters</button>
            <a href="{{ route('catalog') }}" class="btn btn-steam-dark btn-sm"><i class="bi bi-x-circle me-1"></i>Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Side: Games Grid -->
    <div class="col-lg-9">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($games as $game)
          <div class="col">
            <div class="card card-steam h-100 border border-secondary shadow-sm hover-scale">
              <div class="position-relative">
                <img src="{{ $game->image }}" class="card-img-top" alt="{{ $game->title }}" style="height: 160px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
                
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
                  <h5 class="card-title text-truncate text-light mb-1">{{ $game->title }}</h5>
                  <p class="text-muted small mb-2">{{ $game->publisher->name }}</p>
                  
                  <div class="mb-3">
                    <span class="badge badge-steam-category">{{ $game->category->name }}</span>
                  </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top border-secondary">
                  <span class="text-steam-success fw-bold"><i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($game->rating / 2, 1) }}<span class="text-muted small">/5</span></span>
                  <div class="d-flex gap-1 align-items-stretch">
                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex">
                      @csrf
                      <input type="hidden" name="game_id" value="{{ $game->id }}">
                      <button type="submit" class="btn-catalog btn-catalog-cart" title="Add to Cart"><i class="bi bi-cart-plus"></i></button>
                    </form>
                    
                    @if(Auth::check())
                      <form action="{{ route('wishlist.toggle') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->id }}">
                        <button type="submit" class="btn-catalog btn-catalog-wish" title="Toggle Wishlist"><i class="bi bi-heart-fill"></i></button>
                      </form>
                    @endif
                    
                    <a href="{{ route('detail', $game->id) }}" class="btn-catalog btn-catalog-view">View</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12 text-center py-5">
            <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
            <h4 class="text-muted">No games match your search criteria.</h4>
            <p class="text-muted">Try resetting the filters or modifying your keywords.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

</div>
@endsection
