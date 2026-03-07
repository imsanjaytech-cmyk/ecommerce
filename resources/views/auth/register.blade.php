@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height:80vh;">
    <div class="card shadow-lg border-0 rounded-4 p-4" style="width:100%;max-width:450px;">
        
        <h3 class="text-center mb-4 fw-bold">Create Account</h3>

        <form method="POST" action="{{ route('register') }}" autocomplete="on">
            @csrf

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" 
                       name="name"
                       class="form-control rounded-3 @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       autocomplete="name"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email"
                       name="email"
                       class="form-control rounded-3 @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       autocomplete="email"
                       required>
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
                       autocomplete="new-password"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control rounded-3"
                       autocomplete="new-password"
                       required>
            </div>

            <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
                Register
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    Already have an account? Login
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
