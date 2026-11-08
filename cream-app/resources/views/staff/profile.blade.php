@extends('layouts.dashboard')

@section('title', 'Profile & security')
@section('heading', 'Profile & security')
@section('sub', 'Your account, password and two-factor authentication.')

@php
    $twoFaEnabled = ! is_null($user->two_factor_confirmed_at);
    $twoFaPending = $user->two_factor_secret && is_null($user->two_factor_confirmed_at);
    $recoveryCodes = $user->two_factor_recovery_codes
        ? json_decode(decrypt($user->two_factor_recovery_codes), true)
        : [];

    $statusMessages = [
        'profile-information-updated' => 'Your profile was updated.',
        'password-updated' => 'Your password was changed.',
        'two-factor-authentication-enabled' => 'Two-factor authentication is on — scan the QR code and confirm with a code.',
        'two-factor-authentication-confirmed' => 'Two-factor authentication is now confirmed.',
        'two-factor-authentication-disabled' => 'Two-factor authentication has been disabled.',
        'recovery-codes-generated' => 'New recovery codes generated.',
    ];
@endphp

@section('content')
    @if (session('status') === 'two-factor-required')
        <div class="alert alert-warn">As an administrator you must set up two-factor authentication before you can use the dashboard. Complete the setup below.</div>
    @elseif (session('status') && isset($statusMessages[session('status')]))
        <div class="alert alert-ok">{{ $statusMessages[session('status')] }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-err">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Profile information --}}
    <div class="section-card">
        <h2>Profile information</h2>
        <div class="desc">Your name and email address.</div>
        <form method="POST" action="{{ route('user-profile-information.update') }}">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="name">Full name</label>
                <input id="name" name="name" class="input" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" class="input" value="{{ old('email', $user->email) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Save profile</button>
        </form>
    </div>

    {{-- Password --}}
    <div class="section-card">
        <h2>Change password</h2>
        <div class="desc">At least 12 characters, checked against known breached passwords. Changing your password signs out your other sessions.</div>
        <form method="POST" action="{{ route('user-password.update') }}">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="current_password">Current password</label>
                <input id="current_password" name="current_password" type="password" class="input" autocomplete="current-password" required>
            </div>
            <div class="field">
                <label for="new_password">New password</label>
                <input id="new_password" name="password" type="password" class="input" autocomplete="new-password" required>
            </div>
            <div class="field">
                <label for="new_password_confirmation">Confirm new password</label>
                <input id="new_password_confirmation" name="password_confirmation" type="password" class="input" autocomplete="new-password" required>
            </div>
            <button type="submit" class="btn btn-primary">Change password</button>
        </form>
    </div>

    {{-- Two-factor authentication --}}
    <div class="section-card">
        <h2>Two-factor authentication</h2>
        <div class="desc">
            @if ($user->isAdmin())
                Required for administrators. Use an authenticator app (Google Authenticator, Authy, 1Password…).
            @else
                Optional but recommended. Use an authenticator app (Google Authenticator, Authy, 1Password…).
            @endif
        </div>

        @if (! $user->two_factor_secret)
            <p class="muted">Two-factor authentication is currently <strong>off</strong>.</p>
            <form method="POST" action="{{ route('two-factor.enable') }}">
                @csrf
                <button type="submit" class="btn btn-gold">Enable two-factor</button>
            </form>

        @elseif ($twoFaPending)
            <p><span class="tag gold">Pending confirmation</span></p>
            <p class="muted" style="margin-top:12px">1. Scan this QR code with your authenticator app (or enter the key manually):</p>
            <div class="qr-box">{!! $user->twoFactorQrCodeSvg() !!}</div>
            <p class="hint">Setup key: <code>{{ decrypt($user->two_factor_secret) }}</code></p>

            @if ($recoveryCodes)
                <p class="muted" style="margin-top:14px">2. Save these recovery codes somewhere safe — each can be used once if you lose your device:</p>
                <div class="codes">
                    @foreach ($recoveryCodes as $code)<div>{{ $code }}</div>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('two-factor.confirm') }}" style="margin-top:16px">
                @csrf
                <div class="field" style="max-width:260px">
                    <label for="code">3. Enter a code to confirm</label>
                    <input id="code" name="code" class="input" inputmode="numeric" autocomplete="one-time-code" required>
                </div>
                <button type="submit" class="btn btn-primary">Confirm &amp; turn on</button>
            </form>
            <form method="POST" action="{{ route('two-factor.disable') }}" style="margin-top:10px">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost">Cancel setup</button>
            </form>

        @else
            <p><span class="tag ok">Enabled</span></p>
            @if ($recoveryCodes)
                <p class="muted" style="margin-top:12px">Your recovery codes (each usable once):</p>
                <div class="codes">
                    @foreach ($recoveryCodes as $code)<div>{{ $code }}</div>@endforeach
                </div>
            @endif
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:16px">
                <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost">Regenerate recovery codes</button>
                </form>
                <form method="POST" action="{{ route('two-factor.disable') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Disable two-factor</button>
                </form>
            </div>
        @endif
    </div>

    {{-- Sessions --}}
    <div class="section-card">
        <h2>Other sessions</h2>
        <div class="desc">Sign out of your account everywhere else (other browsers and devices).</div>
        <form method="POST" action="{{ route('staff.profile.logout-others') }}">
            @csrf
            <div class="field" style="max-width:320px">
                <label for="logout_password">Confirm your password</label>
                <input id="logout_password" name="password" type="password" class="input" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-ghost">Sign out other sessions</button>
        </form>
    </div>
@endsection
