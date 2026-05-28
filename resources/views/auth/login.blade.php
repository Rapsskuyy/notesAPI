@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div style="display:flex; align-items:center; justify-content:center; min-height:80vh;">
    <div style="width:100%; max-width:420px;">

        <div style="text-align:center; margin-bottom:28px;">
            <h1 style="font-size:2.2rem; font-weight:900; letter-spacing:-2px;">Notes App</h1>
            <p style="font-weight:700; color:#555; margin-top:6px;">Sign in to your account</p>
        </div>

        <div class="card">
            <div class="card-title">LOGIN</div>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        value="{{ old('username') }}"
                        placeholder="Your username"
                        required
                        autocomplete="username"
                    >
                    @error('username')
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
                        placeholder="Your password"
                        required
                        autocomplete="current-password"
                    >
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-yellow" style="width:100%; margin-top:8px; font-size:1rem; padding:12px;">
                    LOGIN →
                </button>
            </form>
        </div>

        <div style="text-align:center; font-weight:700; margin-top:16px;">
            Don't have an account?
            <a href="{{ route('register') }}" style="color:#000; font-weight:900;">Register here →</a>
        </div>
    </div>
</div>
@endsection
