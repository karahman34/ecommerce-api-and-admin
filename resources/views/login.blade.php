@extends('layouts.auth.layout')

@section('content')
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
      <div class="card card-primary">
        <div class="card-header">
          <h4>Login</h4>
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
            @csrf

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" class="form-control" name="email" tabindex="1" placeholder="Email" required autofocus>
              @error('email')
                <div class="text-danger">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <div class="d-block">
                <label for="password" class="control-label">Password</label>
                <div class="float-right">
                  <a href="{{ route('password.request') }}" class="text-small">
                    Forgot Password?
                  </a>
                </div>
              </div>
              <input id="password" type="password" class="form-control" name="password" tabindex="2" placeholder="Password" required>
              @error('password')
                <div class="text-danger">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                <label class="custom-control-label" for="remember-me">Remember Me</label>
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                Login
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
