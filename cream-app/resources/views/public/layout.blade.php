@php
    $ogImageUrl = isset($ogImage) && $ogImage
        ? (\Illuminate\Support\Str::startsWith($ogImage, 'http') ? $ogImage : url($ogImage))
        : url('/assets/royal-manhattan.png');
    $canonicalUrl = isset($canonical) ? url($canonical) : url()->current();
    $includePopup = $includePopup ?? true;
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{{ $title ?? config('cream.legal_name') }}</title>
    <meta name="description" content="{{ $description ?? '' }}"/>
    <link rel="canonical" href="{{ $canonicalUrl }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{ $title ?? config('cream.legal_name') }}"/>
    <meta property="og:description" content="{{ $description ?? '' }}"/>
    <meta property="og:url" content="{{ $canonicalUrl }}"/>
    <meta property="og:image" content="{{ $ogImageUrl }}"/>
    <meta property="og:site_name" content="{{ config('cream.legal_name') }}"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{{ $title ?? config('cream.legal_name') }}"/>
    <meta name="twitter:description" content="{{ $description ?? '' }}"/>
    <meta name="twitter:image" content="{{ $ogImageUrl }}"/>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml"/>
    <link rel="icon" href="/favicon.svg" sizes="any"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="/assets/base.css"/>
    <link rel="stylesheet" href="/assets/site.css"/>
    <link rel="stylesheet" href="/assets/pages.css"/>
    @isset($jsonld)
        <script type="application/ld+json">{!! $jsonld !!}</script>
    @endisset
    @isset($extraCss)
        <style>{!! $extraCss !!}</style>
    @endisset
    @stack('head')
</head>
<body @isset($bodyClass) class="{{ $bodyClass }}" @endisset>
    @include('public.partials.header', ['active' => $active ?? ''])
    <main id="main">
        @yield('content')
    </main>
    @include('public.partials.footer')
    @include('public.partials.mobilebar')
    @if ($includePopup)
        @include('public.partials.popup')
    @endif
    @stack('scripts')
    <script src="/assets/site.js"></script>
</body>
</html>
