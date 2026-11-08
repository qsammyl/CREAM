@extends('layouts.auth')

@section('title', 'Choose a new password')

@section('content')
    <h1>New password</h1>
    <p class="lede">Choose a new password for your account.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email"
                   value="{{ old('email', $request->email) }}"
                   class="input @error('email') err @enderror" required autocomplete="username">
            @error('email')<div class="field-err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password">New password</label>
            <input id="password" name="password" type="password"
                   class="input @error('password') err @enderror" required autocomplete="new-password">
            <div class="hint">At least 12 characters. Checked against known breached passwords.</div>
            @error('password')<div class="field-err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   class="input" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Reset password</button>
    </form>
@endsection
