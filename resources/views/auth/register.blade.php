@extends('layouts.app')

@section('title', 'Create Account - GameHub')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card-steam-static">
        
        <!-- Header -->
        <div class="text-center mb-4">
          <i class="bi bi-controller text-steam-accent fs-1"></i>
          <h2 class="mt-2">CREATE AN ACCOUNT</h2>
          <p class="text-muted small">Join GameHub to start browsing and purchasing games</p>
        </div>

        @if($errors->any())
          <div class="alert alert-danger bg-opacity-25 bg-danger border-danger text-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Form -->
        <form action="{{ route('auth.register') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="username" class="form-label text-muted">CHOOSE A USERNAME</label>
            <input type="text" name="username" id="username" class="form-control form-control-steam" placeholder="Your username" required value="{{ old('username') }}">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label text-muted">EMAIL ADDRESS</label>
            <input type="email" name="email" id="email" class="form-control form-control-steam" placeholder="example@domain.com" required value="{{ old('email') }}">
          </div>

          <div class="mb-4">
            <label for="password" class="form-label text-muted">PASSWORD</label>
            <input type="password" name="password" id="password" class="form-control form-control-steam" placeholder="••••••••" required>
            <div class="form-text text-muted" style="font-size: 0.75rem;">Minimum 4 characters.</div>
          </div>

          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-steam-primary py-2">Create Account</button>
          </div>
        </form>

        <hr class="border-secondary my-4">

        <!-- Footer link -->
        <div class="text-center text-muted">
          Already have an account? <a href="{{ route('auth.login.view') }}" class="text-steam-accent">Sign In</a>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
