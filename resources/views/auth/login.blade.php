@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="card shadow-lg border-0 rounded-4 p-4" style="width:100%;max-width:420px;">
        
        <h3 class="text-center mb-4 fw-bold">Login</h3>

        <form method="POST" action="{{ route('login') }}" autocomplete="on">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" 
                       name="email" 
                       class="form-control rounded-3 @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" 
                       autocomplete="email"
                       required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" 
                       name="password" 
                       class="form-control rounded-3 @error('password') is-invalid @enderror"
                       autocomplete="current-password"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember">
                <label class="form-check-label">
                    Remember me
                </label>
            </div>

            {{-- Login Button --}}
            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">
                Login
            </button>

            {{-- Divider --}}
            <div class="text-center my-3">
                <small class="text-muted">OR</small>
            </div>

            {{-- Google Login --}}
            <a href="#" 
               class="btn btn-light border w-100 rounded-pill py-2 d-flex align-items-center justify-content-center">
                <img src="https://developers.google.com/identity/images/g-logo.png" 
                     width="18" class="me-2">
                Continue with Google
            </a>

            {{-- Register Link --}}
            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="text-decoration-none">
                    Don't have an account? Register
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
