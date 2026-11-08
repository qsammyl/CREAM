@extends('layouts.auth')

@section('title', 'Sign in')

@section('content')
    <h1>Sign in</h1>
    <p class="lede">CREAM staff dashboard.</p>

    @if (session('status'))
        <div class="alert alert-ok">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   class="input @error('email') err @enderror"
                   required autofocus autocomplete="username">
            @error('email')
                <div class="field-err">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password"
                   class="input @error('password') err @enderror"
                   required autocomplete="current-password">
            @error('password')
                <div class="field-err">{{ $message }}</div>
            @enderror
        </div>

        <div class="field" style="display:flex;align-items:center;justify-content:space-between;gap:12px">
            <label class="check"><input type="checkbox" name="remember"> Remember me</label>
            <a href="{{ route('password.request') }}" class="hint" style="color:var(--emerald-3)">Forgot password?</a>
        </div>

        @include('partials.turnstile')

        <button type="submit" class="btn btn-primary btn-block" style="margin-top:6px">Sign in</button>
    </form>
@endsection
