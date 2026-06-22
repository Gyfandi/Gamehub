@extends('layouts.admin')

@section('title', 'Manage Users - GameHub Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-light"><i class="bi bi-people me-2 text-warning"></i>MANAGE USERS</h2>
  <span class="text-muted">{{ count($users) }} accounts registered</span>
</div>

<div class="card-steam-static p-0 overflow-hidden border border-secondary shadow-sm rounded">
  <table class="table table-steam mb-0 align-middle">
    <thead>
      <tr>
        <th scope="col" style="width: 80px;">ID</th>
        <th scope="col">Username</th>
        <th scope="col">Email Address</th>
        <th scope="col">Role</th>
        <th scope="col" class="text-center" style="width: 150px;">Library</th>
        <th scope="col" class="text-center" style="width: 150px;">Reviews</th>
        <th scope="col">Registration Date</th>
        <th scope="col" class="text-center" style="width: 200px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $user)
        <tr>
          <td><strong class="text-light">#{{ $user->id }}</strong></td>
          <td><strong class="text-light">{{ $user->username }}</strong></td>
          <td>{{ $user->email }}</td>
          <td>
            <span class="badge {{ $user->role === 'admin' ? 'bg-warning text-dark' : 'bg-secondary text-white' }} px-2 py-1 text-uppercase">
              {{ $user->role }}
            </span>
          </td>
          <td class="text-center text-info fw-bold">{{ $user->libraries_count }}</td>
          <td class="text-center text-success fw-bold">{{ $user->reviews_count }}</td>
          <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
          <td class="text-center">
            <!-- Form to toggle role -->
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'buyer' : 'admin' }}">
              
              <!-- Prevent logged-in user from changing their own role (self-demotion protection) -->
              @if(Auth::id() == $user->id)
                <button type="button" class="btn btn-steam-dark btn-sm" disabled title="You cannot demote yourself">
                  <i class="bi bi-shield-slash me-1"></i>Protected
                </button>
              @else
                <button type="submit" class="btn btn-outline-info btn-sm">
                  <i class="bi bi-arrow-left-right me-1"></i>Toggle Role
                </button>
              @endif
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
