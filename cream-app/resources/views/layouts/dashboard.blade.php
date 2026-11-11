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

            <div class="nav-sec">Content</div>
            <a href="{{ route('staff.articles.index') }}" class="{{ request()->routeIs('staff.articles.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 3h10l4 4v14H5z"/><path d="M15 3v4h4M8 13h8M8 17h8M8 9h3"/></svg>
                Articles
            </a>
            <a href="{{ route('staff.comments.index') }}" class="{{ request()->routeIs('staff.comments.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Comments
                @php($pendingComments = \App\Models\Comment::where('status', 'pending')->count())
                @if ($pendingComments)<span class="pill">{{ $pendingComments }}</span>@endif
            </a>
            <a href="{{ route('staff.testimonials.index') }}" class="{{ request()->routeIs('staff.testimonials.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 3l2.9 5.9 6.1.9-4.4 4.3 1 6.1-5.6-2.9-5.6 2.9 1-6.1L2 9.8l6.1-.9z"/></svg>
                Testimonials
                @php($pendingTestimonials = \App\Models\Testimonial::where('status', 'pending')->count())
                @if ($pendingTestimonials)<span class="pill">{{ $pendingTestimonials }}</span>@endif
            </a>
            <a href="{{ route('staff.enquiries.index') }}" class="{{ request()->routeIs('staff.enquiries.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                Enquiries
                @php($newEnquiries = \App\Models\Enquiry::where('status', 'new')->count())
                @if ($newEnquiries)<span class="pill">{{ $newEnquiries }}</span>@endif
            </a>

            <a href="{{ route('staff.profile') }}" class="{{ request()->routeIs('staff.profile') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                Profile &amp; security
            </a>
            @if ($me->isAdmin())
                <div class="nav-sec">Admin</div>
                <a href="{{ route('staff.users.index') }}" class="{{ request()->routeIs('staff.users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="8" r="3.2"/><path d="M3 20a6 6 0 0 1 12 0"/><path d="M16 5a3 3 0 0 1 0 6M18 20a6 6 0 0 0-3-5.2"/></svg>
                    Staff users
                </a>
                <a href="{{ route('staff.settings') }}" class="{{ request()->routeIs('staff.settings*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.6 1.6 0 0 0-2.7 1.1V21a2 2 0 1 1-4 0v-.1A1.6 1.6 0 0 0 7 19.4l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1A1.6 1.6 0 0 0 4.6 15H4a2 2 0 1 1 0-4h.1A1.6 1.6 0 0 0 6 8.6l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1A1.6 1.6 0 0 0 11 4.6V4a2 2 0 1 1 4 0v.1a1.6 1.6 0 0 0 2.7 1.1l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.6 1.6 0 0 0-.3 1.8 1.6 1.6 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.6 1.6 0 0 0-1.5 1z"/></svg>
                    Settings
                </a>
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
            @if (session('flash_error'))
                <div class="alert" style="background:#FFF8EC;border:1px solid var(--warn-bd);color:var(--warn-ink)">{{ session('flash_error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script src="/assets/dashboard.js" defer></script>
@stack('scripts')
</body>
</html>
