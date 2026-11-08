@extends('layouts.auth')

@section('title', 'Reset password')

@section('content')
    <h1>Reset password</h1>
    <p class="lede">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
        <div class="alert alert-ok">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   class="input @error('email') err @enderror" required autofocus autocomplete="username">
            @error('email')<div class="field-err">{{ $message }}</div>@enderror
        </div>

        @include('partials.turnstile')

        <button type="submit" class="btn btn-primary btn-block">Email reset link</button>
        <a href="{{ route('login') }}" class="btn btn-ghost btn-block" style="margin-top:10px">Back to sign in</a>
    </form>
@endsection
