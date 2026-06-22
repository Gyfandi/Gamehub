@extends('layouts.app')

@section('title', 'My Library - GameHub')

@section('content')
<div class="container">
  
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-light"><i class="bi bi-controller me-2 text-steam-accent"></i>MY LIBRARY</h2>
    <span class="text-muted">{{ count($ownedGames) }} games owned</span>
  </div>

  <div class="row">
    @if(count($ownedGames) > 0)
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($ownedGames as $game)
          <div class="col" id="game-card-{{ $game['id'] }}">
            <div class="card card-steam h-100 border border-secondary shadow-sm hover-scale">
              <div class="position-relative">
                <img src="{{ $game['image'] }}" class="card-img-top" alt="{{ $game['title'] }}" style="height: 180px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
                <span class="position-absolute top-0 start-0 m-2 badge bg-success text-white py-1 px-2 border border-dark" id="status-badge-{{ $game['id'] }}">
                  Ready
                </span>
              </div>
              <div class="card-body d-flex flex-column justify-content-between bg-dark bg-opacity-25">
                <div>
                  <h4 class="card-title text-truncate text-light mb-1 fs-5">{{ $game['title'] }}</h4>
                  <div class="text-muted small mb-1">
                    <i class="bi bi-calendar-event me-1"></i>Purchased: {{ $game['purchase_date'] }}
                  </div>
                  <div class="text-steam-accent small mb-3">
                    <i class="bi bi-clock me-1"></i>Playtime: {{ $game['playtime'] }} (Last: {{ $game['last_played'] }})
                  </div>
                  
                  <!-- Download Progress Bar Wrapper (Hidden by default) -->
                  <div class="mb-3 d-none" id="progress-wrapper-{{ $game['id'] }}">
                    <div class="d-flex justify-content-between text-muted small mb-1">
                      <span>Downloading...</span>
                      <span id="progress-text-{{ $game['id'] }}">0%</span>
                    </div>
                    <div class="progress bg-dark" style="height: 8px; border: 1px solid var(--border-subtle)">
                      <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="progress-bar-{{ $game['id'] }}"></div>
                    </div>
                  </div>
                </div>

                <div class="mt-auto pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                  <a href="{{ route('detail', $game['id']) }}" class="btn btn-steam-dark btn-sm"><i class="bi bi-info-circle me-1"></i>Store Page</a>
                  
                  <!-- Download/Play Interactive Button -->
                  <button type="button" class="btn btn-steam-primary btn-sm" id="btn-action-{{ $game['id'] }}" onclick="startDownload({{ $game['id'] }})">
                    <i class="bi bi-download me-1"></i>Download
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="col-12 text-center py-5">
        <i class="bi bi-journal-album fs-1 text-muted d-block mb-3"></i>
        <h3 class="text-muted">Your Library is empty</h3>
        <p class="text-muted">You haven't purchased any games yet. Head to the store to make your first purchase!</p>
        <a href="{{ route('catalog') }}" class="btn btn-steam-primary mt-3 px-4 py-2"><i class="bi bi-shop me-2"></i>Go to Store</a>
      </div>
    @endif
  </div>

</div>
@endsection

@section('scripts')
<script>
  function startDownload(gameId) {
    const btn = document.getElementById('btn-action-' + gameId);
    const badge = document.getElementById('status-badge-' + gameId);
    
    // Check if already playing
    if (btn.classList.contains('btn-steam-success')) {
      alert('Launching game... Have fun!');
      return;
    }

    // Disable button during download
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat spin-icon"></i> Installing...';

    // Show progress wrapper
    const wrapper = document.getElementById('progress-wrapper-' + gameId);
    wrapper.classList.remove('d-none');

    const progressBar = document.getElementById('progress-bar-' + gameId);
    const progressText = document.getElementById('progress-text-' + gameId);

    let percentage = 0;
    const interval = setInterval(() => {
      percentage += 5;
      progressBar.style.width = percentage + '%';
      progressText.innerText = percentage + '%';

      if (percentage >= 100) {
        clearInterval(interval);
        
        // Complete state
        wrapper.classList.add('d-none');
        badge.innerText = 'Ready to Play';
        badge.classList.replace('bg-success', 'bg-info');
        
        btn.disabled = false;
        btn.classList.replace('btn-steam-primary', 'btn-steam-success');
        btn.innerHTML = '<i class="bi bi-play-fill me-1"></i>Play Game';
      }
    }, 150);
  }
</script>

<style>
  .spin-icon {
    display: inline-block;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
@endsection
