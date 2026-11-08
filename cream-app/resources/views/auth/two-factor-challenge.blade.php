@extends('layouts.auth')

@section('title', 'Two-factor')

@section('content')
    <h1>Two-factor</h1>
    <p class="lede">Enter the 6-digit code from your authenticator app, or one of your recovery codes.</p>

    @error('code')<div class="alert alert-err">{{ $message }}</div>@enderror
    @error('recovery_code')<div class="alert alert-err">{{ $message }}</div>@enderror

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div class="field">
            <label for="code">Authentication code</label>
            <input id="code" name="code" class="input" inputmode="numeric"
                   autocomplete="one-time-code" autofocus>
        </div>

        <div class="field">
            <label for="recovery_code">…or recovery code</label>
            <input id="recovery_code" name="recovery_code" class="input" autocomplete="one-time-code">
            <div class="hint">Fill in one of the two fields.</div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Verify</button>
    </form>
@endsection
