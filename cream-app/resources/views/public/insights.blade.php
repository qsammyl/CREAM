@extends('public.layout')

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Insights']]"
        h1="Insights &amp;<br/><em>the Home Show.</em>"
        sub="Market notes, the Signature Home Show, property walkthroughs, and our latest writing on buying and building in Ghana."/>

    <section class="sec bone"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>The Signature Home Show</div><h2 class="section-title">Every home tells<br/>a story.</h2></div><div class="right">Each episode steps inside a Credence home — the owners, the brief, the build — and ends in the room they are most proud of.</div></div>
        <div style="display:grid;grid-template-columns:1.3fr 1fr;gap:40px;align-items:center">
            <a href="https://www.youtube.com/watch?v={{ config('cream.show.latest_video_id') }}" target="_blank" rel="noopener" style="position:relative;display:block;aspect-ratio:16/9;border-radius:var(--r-l);overflow:hidden;box-shadow:var(--shadow-3)">
                <img src="/assets/royal-manhattan.png" alt="Latest Signature Home Show episode" style="width:100%;height:100%;object-fit:cover"/>
                <span style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:76px;height:76px;border-radius:50%;background:rgba(255,255,255,.92);display:grid;place-items:center"><svg width="28" height="28" viewBox="0 0 24 24" fill="#1A1815"><path d="M8 5v14l11-7z"/></svg></span>
                <span style="position:absolute;top:18px;left:18px;background:rgba(0,0,0,.55);color:#fff;backdrop-filter:blur(8px);padding:8px 14px;border-radius:999px;font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase">Latest episode</span>
            </a>
            <div>
                <p class="prose" style="color:var(--ink-2)">Experience extraordinary homes and unforgettable stories — where architecture, design, and lifestyle come together to inspire audiences across Ghana and beyond.</p>
                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:12px"><a class="btn-solid" href="https://www.youtube.com/watch?v={{ config('cream.show.latest_video_id') }}" target="_blank" rel="noopener">Watch latest <x-icon name="arrow"/></a><a class="btn-ghost" href="{{ config('cream.show.channel') }}" target="_blank" rel="noopener">All episodes</a></div>
            </div>
        </div>
    </div></section>

    <section class="sec bone2"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Property walkthroughs</div><h2 class="section-title">Step inside<br/>our homes.</h2></div><div class="right">Video walkthroughs of homes currently or recently on our books. Tap any card to open the full listing.</div></div>
        <div class="media-grid">
            @foreach ($walkthroughs as $l)
                @php($cover = $l->cover())
                <a class="media-card" href="/listings/{{ $l->slug }}"><div class="mc-top">@if ($cover)<img src="{{ $cover->url() }}" alt="{{ $l->title }}"/>@else<x-placeholder :label="$l->title"/>@endif</div><div class="mc-body"><div class="mc-k">Walkthrough</div><div class="mc-t">{{ $l->title }}</div><div class="mc-meta"><span>{{ trim(explode('·', (string) $l->location)[0]) }}</span><span>{{ $l->isPriceOnRequest() ? 'Price on request' : $l->formattedPrice() }}</span></div></div></a>
            @endforeach
        </div>
    </div></section>

    <section class="sec bone"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>From the blog</div><h2 class="section-title">Reading room.</h2></div><div class="right"><a class="btn-ghost" href="/blog">All posts <x-icon name="arrow"/></a></div></div>
        <p class="prose" style="color:var(--ink-2)">Practical writing on buying, building, renting and managing property in Ghana — for local and diaspora clients.</p>
    </div></section>

    <section class="sec bone2"><div class="container">
        <div class="eyebrow"><span class="dot"></span>Market notes</div>
        <h2 class="t" style="margin-top:10px">Accra market notes</h2>
        <p class="prose" style="color:var(--ink-2);margin-top:14px">Short, practical notes on the Accra market — pricing by area, what diaspora buyers should watch, and documentation trends.</p>
        {{-- TODO(sam): supply real market notes / statistics for this section. No figures are invented here. --}}
        <p class="small" style="margin-top:10px">Market notes are being prepared. <a href="/contact">Ask us a specific question</a> in the meantime.</p>
    </div></section>
@endsection
