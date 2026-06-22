@extends('layouts.app')

@section('title', $game->title . ' - GameHub')

@section('content')
<div class="container">
  
  <!-- Breadcrumb navigation -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb" style="font-size: 0.9rem;">
      <li class="breadcrumb-item"><a href="{{ route('landing') }}">Store</a></li>
      <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Games</a></li>
      <li class="breadcrumb-item active text-light" aria-current="page">{{ $game->title }}</li>
    </ol>
  </nav>

  <!-- Title Header -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h1 class="m-0 text-light fw-bold">{{ $game->title }}</h1>
    @if(Auth::check())
      <form action="{{ route('wishlist.toggle') }}" method="POST">
        @csrf
        <input type="hidden" name="game_id" value="{{ $game->id }}">
        <button type="submit" class="btn btn-steam-dark btn-sm px-3 border border-secondary text-danger">
          <i class="bi bi-heart{{ $isWishlisted ? '-fill' : '' }} me-2"></i>
          {{ $isWishlisted ? 'Wishlisted' : 'Add to Wishlist' }}
        </button>
      </form>
    @endif
  </div>

  <div class="row">
    <!-- Left Column: Media & Description -->
    <div class="col-lg-8 mb-4">
      <!-- Image Gallery -->
      <div class="mb-4">
        <img id="mainGalleryImage" src="{{ $game->image }}" alt="{{ $game->title }}" class="w-100 rounded border border-secondary steam-gallery-main shadow-lg" style="height: 400px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
        <div class="steam-gallery-thumbs mt-2 d-flex gap-2">
          @forelse($game->images as $index => $img)
            <img src="{{ $img->path }}" class="steam-gallery-thumb {{ $index === 0 ? 'active' : '' }} rounded border border-secondary" style="height: 60px; width: 100px; object-fit: cover; cursor: pointer;" onclick="swapGallery(this)" alt="Screenshot {{ $index + 1 }}" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
          @empty
            <img src="{{ $game->image }}" class="steam-gallery-thumb active rounded border border-secondary" style="height: 60px; width: 100px; object-fit: cover; cursor: pointer;" onclick="swapGallery(this)" alt="Cover" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
          @endforelse
        </div>
      </div>

      <!-- Description Block -->
      <div class="card-steam-static mb-4 p-4 border border-secondary rounded">
        <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">ABOUT THIS GAME</h4>
        <p class="text-light" style="white-space: pre-line; line-height: 1.7; font-size: 0.95rem;">{{ $game->description }}</p>
      </div>

      <!-- System Requirements -->
      @if($requirements)
      <div class="card-steam-static mb-4 p-4 border border-secondary rounded">
        <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">SYSTEM REQUIREMENTS</h4>
        <div class="row text-muted" style="font-size: 0.9rem;">
          <div class="col-md-6 mb-2">
            <span class="d-block"><strong>OS:</strong> {{ $requirements['os'] }}</span>
            <span class="d-block"><strong>Processor:</strong> {{ $requirements['processor'] }}</span>
            <span class="d-block"><strong>Memory:</strong> {{ $requirements['memory'] }}</span>
          </div>
          <div class="col-md-6 mb-2">
            <span class="d-block"><strong>Graphics:</strong> {{ $requirements['graphics'] }}</span>
            <span class="d-block"><strong>Storage:</strong> {{ $requirements['storage'] }}</span>
          </div>
        </div>
      </div>
      @endif

      <!-- Reviews Section -->
      <div class="card-steam-static p-4 border border-secondary rounded">
        <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">CUSTOMER REVIEWS</h4>
        
        <!-- Review Rating stats percentage -->
        @if($posPercentage !== null)
          <div class="mb-4 d-flex align-items-center">
            <div class="bg-dark p-3 rounded border border-secondary d-inline-flex align-items-center">
              <span class="text-steam-success fs-4 fw-bold me-2">{{ $posPercentage }}%</span>
              <span class="text-muted small">of the reviews recommend this game</span>
            </div>
          </div>
        @endif

        <!-- Add Review Form (Only for Owners) -->
        @if($isOwned)
          <div class="bg-dark p-3 rounded border border-secondary mb-4">
            <h5 class="text-steam-accent mb-3"><i class="bi bi-pencil-square me-2"></i>Write a Review</h5>
            <form action="{{ route('review.store') }}" method="POST">
              @csrf
              <input type="hidden" name="game_id" value="{{ $game->id }}">
              
              <div class="mb-3">
                <label class="form-label text-muted small fw-bold">RECOMMENDATION</label>
                <div class="d-flex gap-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="recommendation" value="1" id="rec_yes" checked>
                    <label class="form-check-label text-steam-success fw-bold" for="rec_yes">
                      <i class="bi bi-hand-thumbs-up-fill me-1"></i> Yes, Recommend
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="recommendation" value="0" id="rec_no">
                    <label class="form-check-label text-danger fw-bold" for="rec_no">
                      <i class="bi bi-hand-thumbs-down-fill me-1"></i> No, Not Recommended
                    </label>
                  </div>
                </div>
              </div>

              <!-- Rating Score (1 to 5) -->
              <div class="mb-3">
                <label class="form-label text-muted small fw-bold">YOUR RATING (1-5)</label>
                <div class="rating-stars-interactive">
                  <input type="radio" name="rating" value="5" id="star5" required><label for="star5" class="bi bi-star-fill"></label>
                  <input type="radio" name="rating" value="4" id="star4"><label for="star4" class="bi bi-star-fill"></label>
                  <input type="radio" name="rating" value="3" id="star3"><label for="star3" class="bi bi-star-fill"></label>
                  <input type="radio" name="rating" value="2" id="star2"><label for="star2" class="bi bi-star-fill"></label>
                  <input type="radio" name="rating" value="1" id="star1"><label for="star1" class="bi bi-star-fill"></label>
                </div>
              </div>

              <!-- Comment input -->
              <div class="mb-3">
                <label for="comment" class="form-label text-muted small fw-bold">REVIEW CONTENT</label>
                <textarea name="comment" id="comment" rows="3" class="form-control form-control-steam" placeholder="Write your thoughts about this game..." required></textarea>
              </div>

              <button type="submit" class="btn btn-steam-primary btn-sm">Submit Review</button>
            </form>
          </div>
        @elseif(Auth::check())
          <div class="alert alert-info bg-opacity-10 bg-info border-info text-info mb-4" style="font-size: 0.9rem;">
            <i class="bi bi-info-circle me-2"></i>You must purchase this game to leave a review.
          </div>
        @else
          <div class="alert alert-warning bg-opacity-10 bg-warning border-warning text-warning mb-4" style="font-size: 0.9rem;">
            <i class="bi bi-info-circle me-2"></i>Please <a href="{{ route('auth.login.view') }}" class="fw-bold">login</a> to write a review.
          </div>
        @endif

        <!-- Reviews List -->
        <div class="reviews-list">
          @forelse($game->reviews as $review)
            <div class="border-bottom border-secondary pb-3 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center">
                  <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                    {{ strtoupper(substr($review->user->username ?? 'U', 0, 1)) }}
                  </div>
                  <strong class="text-light">{{ $review->user->username ?? 'Unknown' }}</strong>
                  <span class="ms-3 badge {{ $review->recommendation ? 'bg-success text-dark' : 'bg-danger text-light' }} small" style="font-size: 0.75rem;">
                    <i class="bi bi-hand-thumbs-{{ $review->recommendation ? 'up' : 'down' }}-fill me-1"></i>
                    {{ $review->recommendation ? 'Recommended' : 'Not Recommended' }}
                  </span>
                </div>
                <span class="text-muted small">{{ $review->created_at->diffForHumans() }}</span>
              </div>
              <div class="rating-stars mb-2">
                @for($i = 1; $i <= 5; $i++)
                  <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                @endfor
              </div>
              <p class="text-light mb-0 small" style="font-style: italic;">"{{ $review->comment }}"</p>
            </div>
          @empty
            <div class="text-center py-4 text-muted">
              <i class="bi bi-chat-left-text fs-2 d-block mb-2"></i>
              No reviews yet. Be the first to share your experience!
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Right Column: Game Meta & Buy Area -->
    <div class="col-lg-4">
      <!-- Purchase Widget -->
      <div class="card-steam-static mb-4 p-4 border border-secondary rounded">
        <h3 class="fs-4 mb-2 text-light">PURCHASE {{ strtoupper($game->title) }}</h3>
        
        <div class="mb-4 py-2 border-bottom border-secondary d-flex justify-content-between align-items-center">
          <span class="text-muted">Price:</span>
          <div>
            @if($game->is_discounted)
              <span class="badge bg-steam-discount me-2">-{{ $game->activeDiscount->percentage }}%</span>
              <span class="text-muted text-decoration-line-through small">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
              <span class="fs-4 fw-bold text-steam-success d-block">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
            @else
              <span class="fs-4 fw-bold text-light">
                {{ $game->price == 0 ? 'Free to Play' : 'Rp ' . number_format($game->price, 0, ',', '.') }}
              </span>
            @endif
          </div>
        </div>

        @if($isOwned)
          <div class="d-grid gap-2">
            <span class="btn btn-secondary disabled text-center"><i class="bi bi-check-circle me-2"></i>Already in Library</span>
            <a href="{{ route('library') }}" class="btn btn-steam-primary"><i class="bi bi-controller me-2"></i>Play Now</a>
          </div>
        @else
          <div class="d-grid gap-2">
            <form action="{{ route('cart.add') }}" method="POST" class="d-grid">
              @csrf
              <input type="hidden" name="game_id" value="{{ $game->id }}">
              <button type="submit" class="btn btn-steam-success py-2"><i class="bi bi-cart3 me-2"></i>Add to Cart</button>
            </form>
            
            <form action="{{ route('cart.add') }}" method="POST" class="d-grid">
              @csrf
              <input type="hidden" name="game_id" value="{{ $game->id }}">
              <input type="hidden" name="buy_now" value="1">
              <button type="submit" class="btn btn-steam-primary py-2"><i class="bi bi-bag-check me-2"></i>Buy Now</button>
            </form>
          </div>
        @endif
      </div>

      <!-- Game Details Widget -->
      <div class="card-steam-static p-4 border border-secondary rounded">
        <h4 class="fs-5 border-bottom border-secondary pb-2 mb-3 text-light">GAME DETAILS</h4>
        
        <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2" style="font-size: 0.9rem;">
          <span class="text-muted">Publisher:</span>
          <strong class="text-light">{{ $game->publisher->name }}</strong>
        </div>

        <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2" style="font-size: 0.9rem;">
          <span class="text-muted">Developer:</span>
          <strong class="text-light">{{ $game->developer }}</strong>
        </div>

        <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2" style="font-size: 0.9rem;">
          <span class="text-muted">Average Rating:</span>
          <strong class="text-warning"><i class="bi bi-star-fill me-1"></i>{{ number_format($game->rating / 2, 1) }} / 5</strong>
        </div>

        <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2" style="font-size: 0.9rem;">
          <span class="text-muted">Release Date:</span>
          <span class="text-light">{{ $game->release_date->format('d M, Y') }}</span>
        </div>

        <div class="mb-2" style="font-size: 0.9rem;">
          <span class="text-muted d-block mb-2">Category:</span>
          <span class="badge badge-steam-category">{{ $game->category->name }}</span>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  function swapGallery(thumb) {
    // Update main image source
    document.getElementById('mainGalleryImage').src = thumb.src;
    
    // Remove active class from other thumbs
    const thumbs = document.querySelectorAll('.steam-gallery-thumb');
    thumbs.forEach(t => t.classList.remove('active'));
    
    // Add active class to clicked thumb
    thumb.classList.add('active');
  }
</script>
@endsection