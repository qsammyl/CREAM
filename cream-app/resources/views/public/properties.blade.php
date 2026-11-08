@extends('public.layout')

@section('content')
    <x-page-hero
        :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Properties']]"
        h1="The Accra<br/><em>Portfolio.</em>"
        sub="A curated selection of homes, land, and new projects — each title-verified and walked by our team before it appears on this page."/>

    <x-filterbar :chips="$chips" countId="propCount"/>

    <section class="sec bone2">
        <div class="container">
            <div style="display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:16px;margin-bottom:28px">
                <div class="eyebrow"><span class="dot"></span>Homes, land &amp; projects</div>
                <div class="small" style="font-family:var(--mono);letter-spacing:.14em;text-transform:uppercase"><span id="propCount">{{ $defaultCount }}</span> listings shown</div>
            </div>
            <div class="cr-grid">
                @foreach ($assets as $a)
                    <x-listing-card :asset="$a"/>
                @endforeach
            </div>
            <div class="cr-empty" style="display:none;margin-top:24px"><h3>No listings match these filters</h3><p>Try a different status or area, or <a href="/contact">tell us your brief</a>.</p></div>
        </div>
    </section>

    @include('public.partials.off-market')
@endsection
