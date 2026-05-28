@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div style="display:flex; align-items:center; justify-content:center; min-height:80vh;">
    <div style="width:100%; max-width:440px;">

        <div style="text-align:center; margin-bottom:28px;">
            <h1 style="font-size:2.2rem; font-weight:900; letter-spacing:-2px;">Notes App</h1>
            <p style="font-weight:700; color:#555; margin-top:6px;">Create your account</p>
        </div>

        <div class="card">
            <div class="card-title">REGISTER</div>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        value="{{ old('username') }}"
                        placeholder="Raden Raffa"
                        required
                        autocomplete="username"
                    >
                    @error('username')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="raffa@example.com"
                        required
                        autocomplete="email"
                    >
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Min. 6 characters"
                        required
                        autocomplete="new-password"
                    >
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Repeat password"
                        required
                        autocomplete="new-password"
                    >
                </div>

                <button type="submit" class="btn btn-yellow" style="width:100%; margin-top:8px; font-size:1rem; padding:12px;">
                    CREATE ACCOUNT →
                </button>
            </form>
        </div>

        <div style="text-align:center; font-weight:700; margin-top:16px;">
            Already have an account?
            <a href="{{ route('login') }}" style="color:#000; font-weight:900;">Login here →</a>
        </div>
    </div>
</div>
@endsection
