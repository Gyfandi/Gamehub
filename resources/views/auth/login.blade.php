@extends('layouts.app')

@section('title', 'Sign In - GameHub')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card-steam-static">
        
        <!-- Header -->
        <div class="text-center mb-4">
          <i class="bi bi-controller text-steam-accent fs-1"></i>
          <h2 class="mt-2">SIGN IN</h2>
          <p class="text-muted small">to your GameHub account</p>
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
        <form action="{{ route('auth.login') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label text-muted">EMAIL ADDRESS</label>
            <input type="email" name="email" id="email" class="form-control form-control-steam" placeholder="example@domain.com" required value="{{ old('email') }}">
          </div>

          <div class="mb-4">
            <label for="password" class="form-label text-muted">PASSWORD</label>
            <input type="password" name="password" id="password" class="form-control form-control-steam" placeholder="••••••••" required>
          </div>

          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-steam-primary py-2">Sign In</button>
          </div>
        </form>

        <hr class="border-secondary my-4">

        <!-- Footer link -->
        <div class="text-center text-muted">
          Don't have a GameHub account? <a href="{{ route('auth.register.view') }}" class="text-steam-accent">Join for free</a>
        </div>



      </div>
    </div>
  </div>
</div>
@endsection
