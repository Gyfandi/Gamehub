@extends('layouts.admin')

@section('title', 'Admin Dashboard - GameHub')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2><i class="bi bi-speedometer2 me-2 text-warning"></i>ADMIN DASHBOARD</h2>
  <span class="text-muted">System Overview</span>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-5">
  <div class="col-md-6 col-lg-3">
    <div class="admin-stat-card border border-secondary shadow-sm rounded p-3 bg-dark">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="admin-stat-value fs-2 text-light fw-bold">{{ $totalUsers }}</div>
          <div class="admin-stat-label text-muted small fw-bold">Total Users</div>
        </div>
        <i class="bi bi-people text-muted fs-1"></i>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="admin-stat-card border border-secondary shadow-sm rounded p-3 bg-dark">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="admin-stat-value fs-2 text-success fw-bold">{{ $totalGames }}</div>
          <div class="admin-stat-label text-muted small fw-bold">Total Games</div>
        </div>
        <i class="bi bi-controller text-success fs-1"></i>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="admin-stat-card border border-secondary shadow-sm rounded p-3 bg-dark">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="admin-stat-value fs-2 text-info fw-bold">{{ $totalTransactions }}</div>
          <div class="admin-stat-label text-muted small fw-bold">Transactions</div>
        </div>
        <i class="bi bi-receipt text-info fs-1"></i>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-3">
    <div class="admin-stat-card border border-secondary shadow-sm rounded p-3 bg-dark">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="admin-stat-value fs-2 text-warning fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
          <div class="admin-stat-label text-muted small fw-bold">Total Revenue</div>
        </div>
        <i class="bi bi-wallet2 text-warning fs-1"></i>
      </div>
    </div>
  </div>
</div>

<!-- Report Modules -->
<div class="row">
  <!-- Top Selling Games -->
  <div class="col-lg-6 mb-4">
    <div class="card-steam-static p-4 border border-secondary rounded shadow-sm">
      <h4 class="border-bottom border-secondary pb-2 mb-3"><i class="bi bi-trophy me-2 text-warning"></i>Top Selling Games</h4>
      <div class="table-responsive">
        <table class="table table-steam align-middle mb-0">
          <thead>
            <tr>
              <th scope="col">Game Name</th>
              <th scope="col" class="text-center">Units Sold</th>
              <th scope="col" class="text-end">Revenue</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topSelling as $row)
              <tr>
                <td><strong class="text-light">{{ $row->game->title ?? 'Unknown Game' }}</strong></td>
                <td class="text-center text-info fw-bold">{{ $row->sales_count }}</td>
                <td class="text-end fw-bold text-light">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted">No sales logged yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Recent System Activities -->
  <div class="col-lg-6 mb-4">
    <div class="card-steam-static p-4 border border-secondary rounded shadow-sm">
      <h4 class="border-bottom border-secondary pb-2 mb-3"><i class="bi bi-activity me-2 text-danger"></i>System Activity Logs</h4>
      <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
        <table class="table table-steam align-middle mb-0">
          <thead>
            <tr>
              <th scope="col">User</th>
              <th scope="col">Action</th>
              <th scope="col">Time</th>
            </tr>
          </thead>
          <tbody>
            @forelse($activityLogs as $log)
              <tr>
                <td><strong class="text-light">{{ $log->user->username ?? 'System' }}</strong></td>
                <td>
                  <span class="text-muted">{{ $log->action }}:</span>
                  <span class="text-light small d-block">{{ $log->description }}</span>
                </td>
                <td class="text-muted small text-nowrap">{{ $log->created_at->diffForHumans() }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted">No activity logs recorded.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Monthly Sales Aggregation -->
  <div class="col-12 mb-4">
    <div class="card-steam-static p-4 border border-secondary rounded shadow-sm">
      <h4 class="border-bottom border-secondary pb-2 mb-3"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Monthly Sales Reports ({{ now()->year }})</h4>
      <div class="table-responsive">
        <table class="table table-steam align-middle mb-0">
          <thead>
            <tr>
              @foreach($chartLabels as $label)
                <th scope="col" class="text-center">{{ $label }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            <tr>
              @foreach($chartData as $val)
                <td class="text-center fw-bold text-steam-success">
                  {{ $val > 0 ? 'Rp ' . number_format($val, 0, ',', '.') : '-' }}
                </td>
              @endforeach
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
