@extends('layouts.admin')

@section('title', 'Manage Discounts - GameHub Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-light"><i class="bi bi-percent me-2 text-warning"></i>MANAGE DISCOUNTS</h2>
  
  <button class="btn btn-steam-success btn-sm animate-hover" type="button" data-bs-toggle="collapse" data-bs-target="#createDiscountForm" aria-expanded="false" aria-controls="createDiscountForm">
    <i class="bi bi-plus-circle me-1"></i>Add New Discount
  </button>
</div>

<!-- Collapse: Create Discount Form -->
<div class="collapse mb-4" id="createDiscountForm">
  <div class="card-steam-static p-4 border border-secondary shadow-sm rounded">
    <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">ADD NEW GAME DISCOUNT</h4>
    <form action="{{ route('admin.discounts.store') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="game_id" class="form-label text-muted small fw-bold">SELECT GAME</label>
          <select name="game_id" id="game_id" class="form-select form-control-steam" required>
            <option value="" disabled selected>Select Game</option>
            @foreach($games as $game)
              <option value="{{ $game->id }}">{{ $game->title }} (Current: Rp {{ number_format($game->price, 0, ',', '.') }})</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label for="percentage" class="form-label text-muted small fw-bold">DISCOUNT PERCENTAGE (%)</label>
          <input type="number" name="percentage" id="percentage" class="form-control form-control-steam" required min="1" max="99" placeholder="E.g., 50">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="start_date" class="form-label text-muted small fw-bold">START DATE</label>
          <input type="date" name="start_date" id="start_date" class="form-control form-control-steam" required value="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-6 mb-3">
          <label for="end_date" class="form-label text-muted small fw-bold">END DATE</label>
          <input type="date" name="end_date" id="end_date" class="form-control form-control-steam" required value="{{ date('Y-m-d', strtotime('+7 days')) }}">
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-steam-dark btn-sm" data-bs-toggle="collapse" data-bs-target="#createDiscountForm">Cancel</button>
        <button type="submit" class="btn btn-steam-success btn-sm">Save Discount</button>
      </div>
    </form>
  </div>
</div>

<!-- Table: Discounts List -->
<div class="card-steam-static p-0 overflow-hidden border border-secondary shadow-sm rounded">
  <table class="table table-steam mb-0 align-middle">
    <thead>
      <tr>
        <th scope="col" style="width: 80px;">ID</th>
        <th scope="col">Game</th>
        <th scope="col" class="text-center" style="width: 150px;">Percentage</th>
        <th scope="col" class="text-center" style="width: 180px;">Start Date</th>
        <th scope="col" class="text-center" style="width: 180px;">End Date</th>
        <th scope="col" class="text-center" style="width: 150px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($discounts as $discount)
        <tr>
          <td><strong class="text-light">#{{ $discount->id }}</strong></td>
          <td>
            <strong class="text-light d-block">{{ $discount->game->title }}</strong>
            <span class="text-muted small">Original Price: Rp {{ number_format($discount->game->price, 0, ',', '.') }}</span>
          </td>
          <td class="text-center">
            <span class="badge bg-success text-dark fw-bold px-2 py-1 fs-6">-{{ $discount->percentage }}%</span>
          </td>
          <td class="text-center text-light">{{ $discount->start_date }}</td>
          <td class="text-center text-light">{{ $discount->end_date }}</td>
          <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
              <!-- Edit Trigger -->
              <button type="button" class="btn btn-outline-warning btn-sm border-0" data-bs-toggle="modal" data-bs-target="#editModal-{{ $discount->id }}">
                <i class="bi bi-pencil-square"></i>
              </button>

              <!-- Delete Trigger -->
              <form action="{{ route('admin.discounts.delete', $discount->id) }}" method="POST" onsubmit="return confirm('Delete this discount?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm border-0"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>

        <!-- Modal: Edit Discount -->
        <div class="modal fade" id="editModal-{{ $discount->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $discount->id }}" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content modal-content-steam border border-secondary shadow-lg">
              <div class="modal-header modal-header-steam">
                <h5 class="modal-title" id="editModalLabel-{{ $discount->id }}">EDIT DISCOUNT #{{ $discount->id }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST">
                @csrf
                <div class="modal-body text-start">
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">GAME</label>
                    <input type="text" class="form-control form-control-steam" disabled value="{{ $discount->game->title }}">
                  </div>

                  <div class="mb-3">
                    <label for="edit_percentage_{{ $discount->id }}" class="form-label text-muted small fw-bold">PERCENTAGE (%)</label>
                    <input type="number" name="percentage" id="edit_percentage_{{ $discount->id }}" class="form-control form-control-steam" required min="1" max="99" value="{{ $discount->percentage }}">
                  </div>

                  <div class="mb-3">
                    <label for="edit_start_{{ $discount->id }}" class="form-label text-muted small fw-bold">START DATE</label>
                    <input type="date" name="start_date" id="edit_start_{{ $discount->id }}" class="form-control form-control-steam" required value="{{ $discount->start_date }}">
                  </div>

                  <div class="mb-3">
                    <label for="edit_end_{{ $discount->id }}" class="form-label text-muted small fw-bold">END DATE</label>
                    <input type="date" name="end_date" id="edit_end_{{ $discount->id }}" class="form-control form-control-steam" required value="{{ $discount->end_date }}">
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
          <td colspan="6" class="text-center py-4 text-muted">No active discounts managed yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
