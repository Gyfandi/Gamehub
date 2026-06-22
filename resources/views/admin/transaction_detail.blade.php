@extends('layouts.admin')

@section('title', 'Transaction Detail #' . $transaction->id . ' - GameHub Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div class="d-flex align-items-center">
    <a href="{{ route('admin.transactions') }}" class="btn btn-steam-dark btn-sm me-3"><i class="bi bi-arrow-left"></i> Back</a>
    <h2 class="m-0 text-light"><i class="bi bi-receipt me-2 text-warning"></i>TRANSACTION DETAILS #{{ $transaction->id }}</h2>
  </div>
  <span class="badge bg-opacity-25 bg-success text-success border border-success px-3 py-2 text-uppercase fs-6">
    Completed
  </span>
</div>

<div class="row">
  <!-- Left Side: Buyer & Invoice Info -->
  <div class="col-lg-5 mb-4">
    <div class="card-steam-static mb-4 p-4 border border-secondary rounded shadow-sm">
      <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">CUSTOMER INFO</h4>
      
      <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2">
        <span class="text-muted">Username:</span>
        <strong class="text-light">{{ $transaction->user->username ?? 'Unknown' }}</strong>
      </div>
      
      <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2">
        <span class="text-muted">Email:</span>
        <strong class="text-light">{{ $transaction->user->email ?? 'Unknown' }}</strong>
      </div>

      <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2">
        <span class="text-muted">Account ID:</span>
        <strong class="text-light">USR-0{{ $transaction->user->id ?? 0 }}</strong>
      </div>
    </div>

    <div class="card-steam-static p-4 border border-secondary rounded shadow-sm">
      <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">INVOICE DETAIL</h4>
      
      <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2">
        <span class="text-muted">Transaction Date:</span>
        <strong class="text-light">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</strong>
      </div>

      <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2">
        <span class="text-muted">Payment Type:</span>
        <strong class="text-steam-accent"><i class="bi bi-wallet2 me-1"></i> {{ ucfirst($transaction->payment_method) }}</strong>
      </div>

      <div class="mb-2 d-flex justify-content-between border-bottom border-secondary pb-2">
        <span class="text-muted">System Reference:</span>
        <strong class="text-light">ORCL-TX-{{ $transaction->id }}</strong>
      </div>
    </div>
  </div>

  <!-- Right Side: Purchased Games List -->
  <div class="col-lg-7">
    <div class="card-steam-static p-4 border border-secondary rounded shadow-sm">
      <h4 class="border-bottom border-secondary pb-2 mb-3 text-light">PURCHASED ITEMS</h4>
      
      <div class="table-responsive">
        <table class="table table-steam align-middle mb-4">
          <thead>
            <tr>
              <th scope="col" style="width: 100px;">Game ID</th>
              <th scope="col">Game Title</th>
              <th scope="col" class="text-end" style="width: 150px;">Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($transaction->details as $detail)
              <tr>
                <td><strong class="text-muted">#{{ $detail->game->id ?? 0 }}</strong></td>
                <td><strong class="text-light">{{ $detail->game->title ?? 'Deleted Game' }}</strong></td>
                <td class="text-end fw-bold text-light">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center bg-dark p-3 rounded border border-secondary" style="font-size: 1.25rem;">
        <span class="text-muted">Total Charged:</span>
        <strong class="text-steam-accent">Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong>
      </div>
    </div>
  </div>
</div>
@endsection
