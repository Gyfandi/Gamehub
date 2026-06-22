@extends('layouts.admin')

@section('title', 'Manage Transactions - GameHub Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-light"><i class="bi bi-receipt me-2 text-warning"></i>MANAGE TRANSACTIONS</h2>
  <span class="text-muted">{{ count($transactions) }} transactions logged</span>
</div>

<div class="card-steam-static p-0 overflow-hidden border border-secondary shadow-sm rounded">
  <table class="table table-steam mb-0 align-middle">
    <thead>
      <tr>
        <th scope="col" style="width: 150px;">Transaction ID</th>
        <th scope="col">Username</th>
        <th scope="col">Date</th>
        <th scope="col">Payment Method</th>
        <th scope="col" class="text-end" style="width: 180px;">Total Price</th>
        <th scope="col" class="text-center" style="width: 150px;">Status</th>
        <th scope="col" class="text-center" style="width: 120px;">Details</th>
      </tr>
    </thead>
    <tbody>
      @forelse($transactions as $trans)
        <tr>
          <td><strong class="text-light">#{{ $trans->id }}</strong></td>
          <td><strong class="text-light">{{ $trans->user->username ?? 'Unknown' }}</strong></td>
          <td>{{ $trans->created_at->format('Y-m-d H:i') }}</td>
          <td><span class="text-info text-capitalize">{{ $trans->payment_method }}</span></td>
          <td class="text-end fw-bold text-light">Rp {{ number_format($trans->total, 0, ',', '.') }}</td>
          <td class="text-center">
            <span class="badge bg-opacity-25 bg-success text-success border border-success px-2 py-1">
              Completed
            </span>
          </td>
          <td class="text-center">
            <a href="{{ route('admin.transactions.detail', $trans->id) }}" class="btn btn-steam-primary btn-sm py-1">
              <i class="bi bi-eye-fill"></i> View
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center py-4 text-muted">No transactions have been logged.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
