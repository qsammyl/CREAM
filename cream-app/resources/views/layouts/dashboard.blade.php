@php($me = auth()->user())
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') · Credence Staff</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500;600&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/dashboard.css">
    @stack('head')
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <span class="mark">C</span>
            <span><b>Credence</b><span>Staff</span></span>
        </div>
        <nav class="nav">
            <a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                Dashboard
            </a>
            <a href="{{ route('staff.assets.index') }}" class="{{ request()->routeIs('staff.assets.index') || request()->routeIs('staff.assets.create') || request()->routeIs('staff.assets.edit') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 9.5 12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg>
                Assets
            </a>
            <a href="{{ route('staff.assets.bin') }}" class="{{ request()->routeIs('staff.assets.bin') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                Bin
            </a>
            <a href="{{ route('staff.profile') }}" class="{{ request()->routeIs('staff.profile') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                Profile &amp; security
            </a>
            @if ($me->isAdmin())
                <div class="nav-sec">Admin</div>
                <a href="{{ route('staff.activity') }}" class="{{ request()->routeIs('staff.activity') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 6h16M4 12h16M4 18h10"/></svg>
                    Activity log
                </a>
            @endif
        </nav>
        <div class="side-foot">
            <div class="av">{{ strtoupper(substr($me->name, 0, 1)) }}</div>
            <div class="who">
                <b>{{ $me->name }}</b>
                <span>{{ $me->role->label() }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Sign out" aria-label="Sign out">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <h1>@yield('heading', 'Dashboard')</h1>
                @hasSection('sub')<div class="sub">@yield('sub')</div>@endif
            </div>
            @yield('actions')
        </header>
        <main class="content">
            @if (session('status') === 'other-sessions-logged-out')
                <div class="alert alert-ok">You have been signed out of all your other sessions.</div>
            @endif
            @if (session('flash'))
                <div class="alert alert-ok">{{ session('flash') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
