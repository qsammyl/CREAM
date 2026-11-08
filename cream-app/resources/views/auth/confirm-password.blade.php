@extends('layouts.auth')

@section('title', 'Confirm password')

@section('content')
    <h1>Confirm password</h1>
    <p class="lede">For your security, please confirm your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password"
                   class="input @error('password') err @enderror" required autofocus autocomplete="current-password">
            @error('password')<div class="field-err">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary btn-block">Confirm</button>
    </form>
@endsection
