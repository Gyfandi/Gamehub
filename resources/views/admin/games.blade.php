@extends('layouts.admin')

@section('title', 'Manage Games - GameHub Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-light"><i class="bi bi-controller me-2 text-warning"></i>MANAGE GAMES</h2>
  
  <button class="btn btn-steam-success btn-sm animate-hover" type="button" data-bs-toggle="collapse" data-bs-target="#createGameForm" aria-expanded="false" aria-controls="createGameForm">
    <i class="bi bi-plus-circle me-1"></i>Add New Game
  </button>
</div>

<!-- Collapse: Create Game Form -->
<div class="collapse mb-4" id="createGameForm">
  <div class="card-steam-static p-4 border border-secondary shadow-sm rounded">
    <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">ADD NEW GAME</h4>
    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="title" class="form-label text-muted small fw-bold">GAME TITLE</label>
          <input type="text" name="title" id="title" class="form-control form-control-steam" required placeholder="E.g., Half-Life 3">
        </div>
        <div class="col-md-4 mb-3">
          <label for="price" class="form-label text-muted small fw-bold">PRICE (IDR)</label>
          <input type="number" name="price" id="price" class="form-control form-control-steam" required placeholder="E.g., 299000">
        </div>
        <div class="col-md-4 mb-3">
          <label for="stock" class="form-label text-muted small fw-bold">STOCK</label>
          <input type="number" name="stock" id="stock" class="form-control form-control-steam" required value="999">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="developer" class="form-label text-muted small fw-bold">DEVELOPER</label>
          <input type="text" name="developer" id="developer" class="form-control form-control-steam" required placeholder="E.g., Valve">
        </div>
        <div class="col-md-6 mb-3">
          <label for="release_date" class="form-label text-muted small fw-bold">RELEASE DATE</label>
          <input type="date" name="release_date" id="release_date" class="form-control form-control-steam" required value="{{ date('Y-m-d') }}">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="publisher_id" class="form-label text-muted small fw-bold">PUBLISHER</label>
          <select name="publisher_id" id="publisher_id" class="form-select form-control-steam" required>
            <option value="" disabled selected>Select Publisher</option>
            @foreach($publishers as $pub)
              <option value="{{ $pub->id }}">{{ $pub->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label for="category_id" class="form-label text-muted small fw-bold">CATEGORY</label>
          <select name="category_id" id="category_id" class="form-select form-control-steam" required>
            <option value="" disabled selected>Select Category</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label for="images" class="form-label text-muted small fw-bold">GAME IMAGES</label>
        <input type="file" name="images[]" id="images" class="form-control form-control-steam" accept="image/*" multiple required>
        <small class="text-muted">Pilih beberapa file sekaligus. Gambar pertama jadi cover utama (tampil di catalog), sisanya jadi galeri di halaman detail.</small>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label text-muted small fw-bold">DESCRIPTION</label>
        <textarea name="description" id="description" rows="3" class="form-control form-control-steam" required placeholder="Provide a detailed game description..."></textarea>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-steam-dark btn-sm" data-bs-toggle="collapse" data-bs-target="#createGameForm">Cancel</button>
        <button type="submit" class="btn btn-steam-success btn-sm">Save Game</button>
      </div>
    </form>
  </div>
</div>

<!-- Table: Games List -->
<div class="card-steam-static p-0 overflow-hidden border border-secondary shadow-sm rounded">
  <table class="table table-steam mb-0 align-middle">
    <thead>
      <tr>
        <th scope="col" style="width: 80px;">ID</th>
        <th scope="col" style="width: 100px;">Cover</th>
        <th scope="col">Title</th>
        <th scope="col">Publisher</th>
        <th scope="col">Category</th>
        <th scope="col" class="text-end" style="width: 130px;">Price</th>
        <th scope="col" class="text-center" style="width: 130px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($games as $game)
        <tr>
          <td><strong class="text-light">#{{ $game->id }}</strong></td>
          <td>
            <img src="{{ $game->image }}" alt="{{ $game->title }}" class="rounded shadow-sm" style="width: 80px; height: 48px; object-fit: cover;" onerror="this.onerror=null; this.src='/images/games/default.jpg';">
          </td>
          <td>
            <strong class="text-light d-block">{{ $game->title }}</strong>
            <span class="text-muted small">Dev: {{ $game->developer }}</span>
          </td>
          <td>{{ $game->publisher->name }}</td>
          <td><span class="badge badge-steam-category">{{ $game->category->name }}</span></td>
          <td class="text-end fw-bold text-light">Rp {{ number_format($game->price, 0, ',', '.') }}</td>
          <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
              <!-- Edit Trigger -->
              <button type="button" class="btn btn-outline-warning btn-sm border-0" data-bs-toggle="modal" data-bs-target="#editModal-{{ $game->id }}">
                <i class="bi bi-pencil-square"></i>
              </button>

              <!-- Delete Trigger -->
              <form action="{{ route('admin.games.delete', $game->id) }}" method="POST" onsubmit="return confirm('Delete game {{ $game->title }}?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm border-0"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>

        <!-- Modal: Edit Game -->
        <div class="modal fade" id="editModal-{{ $game->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $game->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-steam border border-secondary shadow-lg">
              <div class="modal-header modal-header-steam">
                <h5 class="modal-title" id="editModalLabel-{{ $game->id }}">EDIT GAME: {{ $game->title }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-start">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label for="edit_title_{{ $game->id }}" class="form-label text-muted small fw-bold">GAME TITLE</label>
                      <input type="text" name="title" id="edit_title_{{ $game->id }}" class="form-control form-control-steam" required value="{{ $game->title }}">
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="edit_price_{{ $game->id }}" class="form-label text-muted small fw-bold">PRICE (IDR)</label>
                      <input type="number" name="price" id="edit_price_{{ $game->id }}" class="form-control form-control-steam" required value="{{ $game->price }}">
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="edit_stock_{{ $game->id }}" class="form-label text-muted small fw-bold">STOCK</label>
                      <input type="number" name="stock" id="edit_stock_{{ $game->id }}" class="form-control form-control-steam" required value="{{ $game->stock }}">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="edit_dev_{{ $game->id }}" class="form-label text-muted small fw-bold">DEVELOPER</label>
                      <input type="text" name="developer" id="edit_dev_{{ $game->id }}" class="form-control form-control-steam" required value="{{ $game->developer }}">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="edit_date_{{ $game->id }}" class="form-label text-muted small fw-bold">RELEASE DATE</label>
                      <input type="date" name="release_date" id="edit_date_{{ $game->id }}" class="form-control form-control-steam" required value="{{ $game->release_date->format('Y-m-d') }}">
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="edit_img_{{ $game->id }}" class="form-label text-muted small fw-bold">GAME IMAGES</label>
                    <input type="file" name="images[]" id="edit_img_{{ $game->id }}" class="form-control form-control-steam" accept="image/*" multiple>
                    <small class="text-muted">Kosongkan kalau gak mau ganti gambar. Kalau diisi, gambar baru ditambahkan ke akhir galeri (gambar pertama tetap jadi cover).</small>
                  </div>

                  @if($game->images->count())
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">GAME IMAGES (gambar pertama = cover utama)</label>
                    <div class="d-flex flex-wrap gap-2">
                      @foreach($game->images as $index => $img)
                        <div class="position-relative" style="width: 100px;">
                          @if($index === 0)
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-1" style="font-size: 9px; z-index: 2;">COVER</span>
                          @endif

                          <img src="{{ $img->path }}" class="rounded shadow-sm w-100" style="height: 64px; object-fit: cover;">

                          <!-- Toolbar: geser kiri, geser kanan, hapus — sebaris -->
                          <div class="d-flex justify-content-between gap-1 mt-1">
                            <form action="{{ route('admin.games.images.move', $img->id) }}" method="POST">
                              @csrf
                              <input type="hidden" name="direction" value="left">
                              <button type="submit" class="btn btn-outline-light btn-sm py-0 px-1" style="font-size: 11px;" {{ $index === 0 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-left"></i>
                              </button>
                            </form>
                            <form action="{{ route('admin.games.images.move', $img->id) }}" method="POST">
                              @csrf
                              <input type="hidden" name="direction" value="right">
                              <button type="submit" class="btn btn-outline-light btn-sm py-0 px-1" style="font-size: 11px;" {{ $index === $game->images->count() - 1 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-right"></i>
                              </button>
                            </form>
                            <form action="{{ route('admin.games.images.delete', $img->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus gambar ini?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-1" style="font-size: 11px;">
                                <i class="bi bi-x"></i>
                              </button>
                            </form>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                  @endif

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="edit_pub_{{ $game->id }}" class="form-label text-muted small fw-bold">PUBLISHER</label>
                      <select name="publisher_id" id="edit_pub_{{ $game->id }}" class="form-select form-control-steam" required>
                        @foreach($publishers as $pubOpt)
                          <option value="{{ $pubOpt->id }}" {{ $pubOpt->id == $game->publisher_id ? 'selected' : '' }}>
                            {{ $pubOpt->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="edit_cat_{{ $game->id }}" class="form-label text-muted small fw-bold">CATEGORY</label>
                      <select name="category_id" id="edit_cat_{{ $game->id }}" class="form-select form-control-steam" required>
                        @foreach($categories as $catOpt)
                          <option value="{{ $catOpt->id }}" {{ $catOpt->id == $game->category_id ? 'selected' : '' }}>
                            {{ $catOpt->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="edit_desc_{{ $game->id }}" class="form-label text-muted small fw-bold">DESCRIPTION</label>
                    <textarea name="description" id="edit_desc_{{ $game->id }}" rows="4" class="form-control form-control-steam" required>{{ $game->description }}</textarea>
                  </div>
                </div>
                <div class="modal-footer modal-footer-steam">
                  <button type="button" class="btn btn-steam-dark btn-sm" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-steam-primary btn-sm">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      @empty
        <tr>
          <td colspan="7" class="text-center py-4 text-muted">No games managed in Database yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection