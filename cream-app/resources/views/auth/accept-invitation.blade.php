@extends('layouts.auth')

@section('title', 'Accept invitation')

@section('content')
    <h1>Set up your account</h1>
    <p class="lede">You have been invited to the CREAM staff dashboard as <strong>{{ $invitation->role->label() }}</strong>.</p>

    <form method="POST" action="{{ route('invitation.accept', $token) }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" class="input" value="{{ $invitation->email }}" disabled>
        </div>

        <div class="field">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}"
                   class="input @error('name') err @enderror" required autofocus autocomplete="name">
            @error('name')<div class="field-err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password">Password <span class="hint">(min 12 characters)</span></label>
            <input id="password" name="password" type="password"
                   class="input @error('password') err @enderror" required autocomplete="new-password">
            @error('password')<div class="field-err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   class="input" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top:6px">Create account</button>
    </form>
@endsection
