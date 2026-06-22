@extends('layouts.admin')

@section('title', 'Manage Publishers - GameHub Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-light"><i class="bi bi-building me-2 text-warning"></i>MANAGE PUBLISHERS</h2>
</div>

<div class="row">
  <!-- Left Side: Create Publisher Form -->
  <div class="col-lg-4 mb-4">
    <div class="card-steam-static p-4 border border-secondary shadow-sm rounded bg-dark bg-opacity-50">
      <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">ADD NEW PUBLISHER</h4>
      <form action="{{ route('admin.publishers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label text-muted small fw-bold">PUBLISHER NAME</label>
          <input type="text" name="name" id="name" class="form-control form-control-steam" required placeholder="E.g., Bethesda">
        </div>
        <div class="mb-3">
          <label for="description" class="form-label text-muted small fw-bold">DESCRIPTION</label>
          <textarea name="description" id="description" rows="3" class="form-control form-control-steam" placeholder="Optional description..."></textarea>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-steam-success">Add Publisher</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Right Side: Publishers List Table -->
  <div class="col-lg-8">
    <div class="card-steam-static p-0 overflow-hidden border border-secondary shadow-sm rounded">
      <table class="table table-steam mb-0 align-middle">
        <thead>
          <tr>
            <th scope="col" style="width: 100px;">ID</th>
            <th scope="col">Publisher Name</th>
            <th scope="col" class="text-center" style="width: 130px;">Games Count</th>
            <th scope="col" class="text-center" style="width: 130px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($publishers as $pub)
            <tr>
              <td><strong class="text-light">#{{ $pub->id }}</strong></td>
              <td>
                <strong class="text-light d-block">{{ $pub->name }}</strong>
                @if($pub->description)
                  <span class="text-muted small">{{ $pub->description }}</span>
                @endif
              </td>
              <td class="text-center text-info fw-bold">{{ $pub->games_count }}</td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                  <!-- Edit Button -->
                  <button type="button" class="btn btn-outline-warning btn-sm border-0" data-bs-toggle="modal" data-bs-target="#editModal-{{ $pub->id }}">
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <!-- Delete Form -->
                  <form action="{{ route('admin.publishers.delete', $pub->id) }}" method="POST" onsubmit="return confirm('Delete publisher {{ $pub->name }}?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm border-0"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>

            <!-- Modal: Edit Publisher -->
            <div class="modal fade" id="editModal-{{ $pub->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $pub->id }}" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content modal-content-steam border border-secondary">
                  <div class="modal-header modal-header-steam">
                    <h5 class="modal-title" id="editModalLabel-{{ $pub->id }}">EDIT PUBLISHER #{{ $pub->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('admin.publishers.update', $pub->id) }}" method="POST">
                    @csrf
                    <div class="modal-body text-start">
                      <div class="mb-3">
                        <label for="edit_pub_name_{{ $pub->id }}" class="form-label text-muted small fw-bold">PUBLISHER NAME</label>
                        <input type="text" name="name" id="edit_pub_name_{{ $pub->id }}" class="form-control form-control-steam" required value="{{ $pub->name }}">
                      </div>
                      <div class="mb-3">
                        <label for="edit_pub_desc_{{ $pub->id }}" class="form-label text-muted small fw-bold">DESCRIPTION</label>
                        <textarea name="description" id="edit_pub_desc_{{ $pub->id }}" rows="3" class="form-control form-control-steam">{{ $pub->description }}</textarea>
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
              <td colspan="4" class="text-center py-4 text-muted">No publishers available.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
