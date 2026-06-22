@extends('layouts.app')

@section('title', 'GameHub - Welcome to the Store')

@section('content')
<div class="container">
  
  <!-- Featured Game Hero Banner -->
  @if($featured)
  <div class="mb-5">
    <h2 class="text-steam-accent mb-3"><i class="bi bi-fire me-2 text-danger"></i>FEATURED & RECOMMENDED</h2>
    <div class="steam-hero-carousel position-relative overflow-hidden rounded border border-secondary shadow-lg">
      <div class="row g-0">
        <div class="col-lg-8">
          <img src="{{ $featured->image }}" alt="{{ $featured->title }}" class="w-100 img-fluid hero-main-img" style="min-height: 350px; max-height: 450px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
        </div>
        <div class="col-lg-4 d-flex flex-column justify-content-between p-4 bg-dark bg-opacity-75 backdrop-blur">
          <div>
            <h3 class="mb-2 fs-2 text-light fw-bold">{{ $featured->title }}</h3>
            <div class="d-flex flex-wrap gap-2 mb-3">
              <span class="badge badge-steam-rating bg-success bg-opacity-25 text-success border border-success border-opacity-50">
                <i class="bi bg-transparent bi-star-fill me-1 text-warning"></i>{{ number_format($featured->rating / 2, 1) }}<span class="opacity-75">/5</span> Rating
              </span>
              <span class="badge bg-secondary bg-opacity-50 border border-secondary">{{ $featured->category->name }}</span>
            </div>
            <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6;">
              {{ strlen($featured->description) > 200 ? substr($featured->description, 0, 200) . '...' : $featured->description }}
            </p>
            <div class="small text-muted mt-2">
              <span class="d-block"><strong>Developer:</strong> {{ $featured->developer }}</span>
              <span class="d-block"><strong>Publisher:</strong> {{ $featured->publisher->name }}</span>
            </div>
          </div>
          
          <div class="mt-4">
            <div class="d-flex align-items-center justify-content-between bg-black bg-opacity-50 p-3 rounded border border-secondary">
              <div>
                @if($featured->is_discounted)
                  <span class="badge bg-steam-discount me-2">-{{ $featured->activeDiscount->percentage }}%</span>
                  <span class="text-muted text-decoration-line-through small">Rp {{ number_format($featured->price, 0, ',', '.') }}</span>
                  <strong class="fs-5 text-steam-success d-block">Rp {{ number_format($featured->final_price, 0, ',', '.') }}</strong>
                @else
                  <span class="text-muted d-block small">Buy Now</span>
                  <strong class="fs-5 text-light">
                    @if($featured->price == 0)
                      Free to Play
                    @else
                      Rp {{ number_format($featured->price, 0, ',', '.') }}
                    @endif
                  </strong>
                @endif
              </div>
              <div class="d-flex gap-2">
                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                  @csrf
                  <input type="hidden" name="game_id" value="{{ $featured->id }}">
                  <button type="submit" class="btn btn-steam-success shadow-sm">Add to Cart</button>
                </form>
                <a href="{{ route('detail', $featured->id) }}" class="btn btn-steam-dark">Details</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- Special Offers / Discounts Section -->
  @if($specialOffers && $specialOffers->count() > 0)
  <div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="m-0 text-steam-accent"><i class="bi bi-tags-fill me-2 text-success"></i>SPECIAL OFFERS</h3>
      <a href="{{ route('catalog') }}" class="btn btn-steam-dark btn-sm">Browse All Deals <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
      @foreach($specialOffers as $game)
        <div class="col">
          <div class="card card-steam h-100 border border-secondary hover-scale shadow-sm">
            <div class="position-relative overflow-hidden">
              <img src="{{ $game->image }}" class="card-img-top" alt="{{ $game->title }}" style="height: 180px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
              @if($game->is_discounted)
                <span class="position-absolute top-0 start-0 bg-success text-dark font-weight-bold px-2 py-1 m-2 rounded shadow-sm" style="font-size: 0.85rem; font-weight: 800;">
                  -{{ $game->activeDiscount->percentage }}%
                </span>
              @endif
              <span class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 px-2 py-1 m-2 rounded text-light font-weight-bold" style="font-size: 0.8rem;">
                @if($game->is_discounted)
                  <span class="text-decoration-line-through text-muted me-1" style="font-size: 0.75rem;">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                  <span class="text-steam-success">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                @else
                  {{ $game->price == 0 ? 'Free' : 'Rp ' . number_format($game->price, 0, ',', '.') }}
                @endif
              </span>
            </div>
            <div class="card-body d-flex flex-column justify-content-between bg-dark bg-opacity-25">
              <div>
                <h5 class="card-title text-truncate text-light mb-1">{{ $game->title }}</h5>
                <p class="text-muted small mb-2">{{ $game->publisher->name }}</p>
              </div>
              <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-secondary">
                  <span class="text-steam-success fw-bold"><i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($game->rating / 2, 1) }}<span class="text-muted small">/5</span></span>
                <a href="{{ route('detail', $game->id) }}" class="btn btn-steam-primary btn-sm py-1 px-3">View</a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  <!-- New Releases Section -->
  <div class="row mb-5">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0 text-steam-accent"><i class="bi bi-stars text-warning me-2"></i>NEW RELEASES</h3>
        <a href="{{ route('catalog') }}" class="btn btn-steam-dark btn-sm">See All <i class="bi bi-arrow-right"></i></a>
      </div>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @forelse($newReleases as $game)
          <div class="col">
            <div class="card card-steam h-100 border border-secondary hover-scale shadow-sm">
              <div class="position-relative overflow-hidden">
                <img src="{{ $game->image }}" class="card-img-top" alt="{{ $game->title }}" style="height: 180px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
                @if($game->is_discounted)
                  <span class="position-absolute top-0 start-0 bg-success text-dark font-weight-bold px-2 py-1 m-2 rounded shadow-sm" style="font-size: 0.85rem; font-weight: 800;">
                    -{{ $game->activeDiscount->percentage }}%
                  </span>
                @endif
                <span class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 px-2 py-1 m-2 rounded text-light font-weight-bold" style="font-size: 0.8rem;">
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
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-secondary">
                    <span class="text-steam-success fw-bold"><i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($game->rating / 2, 1) }}<span class="text-muted small">/5</span></span>
                  <a href="{{ route('detail', $game->id) }}" class="btn btn-steam-primary btn-sm py-1 px-3">View</a>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12 text-center py-4 text-muted">No games released yet.</div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Popular / Trending Section -->
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0 text-steam-accent"><i class="bi bi-award text-info me-2"></i>POPULAR GAMES</h3>
        <a href="{{ route('catalog') }}?sort=rating_desc" class="btn btn-steam-dark btn-sm">See Top Rated <i class="bi bi-arrow-right"></i></a>
      </div>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @forelse($trending as $game)
          <div class="col">
            <div class="card card-steam h-100 border border-secondary hover-scale shadow-sm">
              <div class="position-relative overflow-hidden">
                <img src="{{ $game->image }}" class="card-img-top" alt="{{ $game->title }}" style="height: 180px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
                @if($game->is_discounted)
                  <span class="position-absolute top-0 start-0 bg-success text-dark font-weight-bold px-2 py-1 m-2 rounded shadow-sm" style="font-size: 0.85rem; font-weight: 800;">
                    -{{ $game->activeDiscount->percentage }}%
                  </span>
                @endif
                <span class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 px-2 py-1 m-2 rounded text-light font-weight-bold" style="font-size: 0.8rem;">
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
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-secondary">
                    <span class="text-steam-success fw-bold"><i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($game->rating / 2, 1) }}<span class="text-muted small">/5</span></span>
                  <a href="{{ route('detail', $game->id) }}" class="btn btn-steam-primary btn-sm py-1 px-3">View</a>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12 text-center py-4 text-muted">No popular games available.</div>
        @endforelse
      </div>
    </div>
  </div>

</div>
@endsection
