@extends('public.layout')

@php
    use App\Enums\AssetCategory;

    $services = [
        ['development', 'Real Estate Development', 'Modern residential and commercial properties combining luxury, functionality, and long-term value.'],
        ['sales', 'Property Sales & Investment', 'Buying a home, acquiring land, or investing in income property — we guide every step.'],
        ['management', 'Property Management', 'Residential and commercial properties managed efficiently — profitable, secure, well-maintained.'],
        ['construction', 'Construction & Project Mgmt.', 'From foundation to finishing — quality control, schedule discipline, and timely delivery.'],
        ['interiors', 'Interior Decor & Styling', 'Premium interiors that transform spaces into elegant, functional, sophisticated environments.'],
        ['land', 'Land Acquisition & Due Diligence', 'Verification, documentation checks, survey coordination, legal guidance — safe transactions.'],
    ];
    $wa = 'https://wa.me/'.config('cream.whatsapp.number');
@endphp

@push('head')
<style>
.hero{position:relative;min-height:100vh;color:#fff;overflow:hidden;background:#0F0E0C;isolation:isolate}
.hero .bg{position:absolute;inset:0}
.hero .bg .slide{position:absolute;inset:0;background-size:cover;background-position:center 40%;opacity:0;transform:scale(1.06);transition:opacity 1.4s ease, transform 8s ease}
.hero .bg .slide.is-active{opacity:1;transform:scale(1)}
.hero .scrim{position:absolute;inset:0;background:linear-gradient(to bottom, rgba(0,0,0,.5) 0%, rgba(0,0,0,.32) 40%, rgba(0,0,0,.72) 100%)}
.hero .content{position:relative;z-index:2;min-height:100vh;display:flex;flex-direction:column;justify-content:flex-end;padding-top:160px;padding-bottom:84px}
.hero .meta-top{position:absolute;top:150px;left:0;right:0}
.hero .badge{display:inline-flex;align-items:center;gap:10px;padding:9px 16px;border-radius:999px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.22);backdrop-filter:blur(8px);font-family:var(--mono);font-size:11px;letter-spacing:.18em;text-transform:uppercase}
.hero .badge .dot{width:6px;height:6px;border-radius:50%;background:var(--gold)}
.hero h1{font-family:var(--serif);font-weight:400;font-size:clamp(54px,8.4vw,132px);line-height:.95;letter-spacing:-0.02em;margin:0;text-shadow:0 2px 30px rgba(0,0,0,.35)}
.hero h1 em{font-style:italic;color:#E8DEC2}
.hero .sub{margin-top:26px;max-width:56ch;font-size:clamp(16px,1.4vw,19px);line-height:1.65;color:rgba(255,255,255,.92);text-shadow:0 1px 18px rgba(0,0,0,.55)}
.hero .cta-row{display:flex;gap:14px;margin-top:34px;flex-wrap:wrap;align-items:center}
.hero .cta-row .btn-solid{background:var(--gold);color:var(--ink)}
.hero .cta-row .btn-solid:hover{background:#fff}
.hero .cta-row .btn-ghost{color:#fff;border-color:rgba(255,255,255,.4)}
.hero .cta-row .btn-ghost:hover{background:#fff;color:var(--ink);border-color:#fff}
.hero .wa-link{display:inline-flex;align-items:center;gap:8px;color:#fff;font-size:14px;opacity:.9;border-bottom:1px solid rgba(255,255,255,.35);padding-bottom:3px}
.hero .wa-link:hover{opacity:1}
.hero .dots{position:absolute;left:var(--gutter);bottom:30px;display:flex;gap:6px;z-index:3}
.hero .dots .d{width:24px;height:2px;background:rgba(255,255,255,.3)}
.hero .dots .d.on{background:#fff}
.feat{background:var(--bone)}
.feat .head{display:flex;justify-content:space-between;align-items:end;gap:24px;flex-wrap:wrap;margin-bottom:32px}
.feat .head h2{font-family:var(--serif);font-weight:400;font-size:clamp(34px,4.6vw,64px);line-height:1.02;letter-spacing:-0.015em;margin:8px 0 0}
.feat .head h2 em{font-style:italic;color:var(--gold)}
.feat .links{display:flex;gap:16px;flex-wrap:wrap;margin-top:40px;justify-content:center}
.svcs{background:var(--bone-2)}
.svc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0;border-top:1px solid var(--line);border-left:1px solid var(--line);background:#fff}
.svc{border-right:1px solid var(--line);border-bottom:1px solid var(--line);padding:38px 30px 30px;display:flex;flex-direction:column;gap:12px;position:relative}
.svc .num{position:absolute;top:18px;right:22px;font-family:var(--mono);font-size:11px;color:var(--ink-4);letter-spacing:.18em}
.svc h3{font-family:var(--serif);font-size:25px;font-weight:400;margin:0;line-height:1.15;letter-spacing:-0.01em}
.svc p{font-size:14.5px;color:var(--ink-3);line-height:1.6;margin:0}
.svc .enq{margin-top:auto;padding-top:16px;font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--emerald);display:inline-flex;align-items:center;gap:8px}
@media(max-width:1080px){.svc-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.svc-grid{grid-template-columns:1fr}}
.why{background:var(--bone)}
.why-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;border-top:1px solid var(--line);margin-top:8px}
.why-item{padding:28px 24px 28px 0;border-bottom:1px solid var(--line)}
.why-item:not(:last-child){border-right:1px solid var(--line);padding-right:24px}
.why-item:not(:first-child){padding-left:24px}
.why-item .n{font-family:var(--mono);font-size:11px;color:var(--gold-2);letter-spacing:.18em}
.why-item .t{font-family:var(--serif);font-size:22px;line-height:1.15;margin-top:12px;letter-spacing:-0.01em}
.why-item .d{font-size:13.5px;color:var(--ink-3);line-height:1.6;margin-top:8px}
@media(max-width:1080px){.why-grid{grid-template-columns:1fr 1fr}.why-item:nth-child(2){border-right:0}}
@media(max-width:640px){.why-grid{grid-template-columns:1fr}.why-item{border-right:0!important;padding-right:0}}
.reels{background:var(--bone-2);border-top:1px solid var(--line)}
.reels .head{display:flex;justify-content:space-between;align-items:end;gap:24px;flex-wrap:wrap;margin-bottom:32px}
.reels .head h2{font-family:var(--serif);font-weight:400;font-size:clamp(32px,4vw,56px);line-height:1.02;margin:8px 0 0}
.reels .head h2 em{font-style:italic;color:var(--gold)}
.reels-row{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.reel{aspect-ratio:9/16;border-radius:var(--r-l);overflow:hidden;position:relative;background:#0F0E0C;color:#fff;display:flex;flex-direction:column;justify-content:flex-end;transition:transform .3s ease,box-shadow .3s ease}
.reel:hover{transform:translateY(-4px);box-shadow:var(--shadow-3)}
.reel video,.reel .ph-brand{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0}
.reel::before{content:"";position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.25) 0%,transparent 40%,rgba(0,0,0,.85) 100%);z-index:1}
.reel .play-ic{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.92);color:var(--ink);display:grid;place-items:center;z-index:2}
.reel .body{position:relative;z-index:2;padding:16px 18px}
.reel .ttl{font-family:var(--serif);font-size:18px;line-height:1.15}
.reel .ttl em{font-style:italic;color:#E8DEC2}
.reel .meta{display:flex;justify-content:space-between;font-family:var(--mono);font-size:10px;letter-spacing:.1em;color:rgba(255,255,255,.8);text-transform:uppercase;margin-top:6px}
.reel .top-row{position:absolute;top:14px;left:14px;right:14px;display:flex;justify-content:space-between;align-items:center;z-index:2}
.reel .tag{font-family:var(--mono);font-size:9.5px;letter-spacing:.16em;text-transform:uppercase;padding:5px 10px;border-radius:999px;background:rgba(255,255,255,.18);backdrop-filter:blur(8px);color:#fff}
.reel .tag.live{background:#D9483B}
.reel .dur{font-family:var(--mono);font-size:10.5px;letter-spacing:.06em;background:rgba(0,0,0,.5);backdrop-filter:blur(6px);padding:4px 8px;border-radius:5px;color:#fff}
.reel .top-row,.reel .body{transition:opacity .3s ease,transform .3s ease}
.reel:hover .top-row,.reel:hover .body{opacity:0;pointer-events:none;transform:translateY(5px)}
/* No-media reels: clean branded preview (image-less), mirroring the main site's video cards */
.reel .ph-brand{background:linear-gradient(165deg,#1C4034 0%,#102A22 48%,#0C0F0D 100%)}
.reel .ph-brand .ph-mark,.reel .ph-brand .ph-word,.reel .ph-brand .ph-sub{display:none}
@media(max-width:1080px){.reels-row{grid-template-columns:1fr 1fr}}
@media(max-width:640px){.reels-row{grid-template-columns:1fr 1fr;gap:12px}}
.test{background:var(--bone)}
.test-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:32px;margin-top:36px}
.qcard{background:var(--paper);border:1px solid var(--line);border-radius:var(--r-l);padding:40px;display:flex;flex-direction:column;gap:18px}
.qcard .mark{font-family:var(--serif);font-size:70px;line-height:.3;color:var(--gold);height:26px}
.qcard .q{font-family:var(--serif);font-size:23px;line-height:1.35;font-style:italic;color:var(--ink)}
.qcard .stars{color:var(--gold);letter-spacing:.18em;font-size:13px}
.qcard .who{display:flex;align-items:center;gap:14px;margin-top:auto;padding-top:14px;border-top:1px solid var(--line)}
.qcard .who .av{width:46px;height:46px;border-radius:50%;background:var(--emerald);color:#fff;display:grid;place-items:center;font-family:var(--serif);font-size:18px;flex:none;overflow:hidden}
.qcard .who .av img{width:100%;height:100%;object-fit:cover;object-position:center 22%}
.qcard .who .n{font-family:var(--serif);font-size:18px}
.qcard .who .r{font-size:12.5px;color:var(--ink-3);margin-top:2px}
@media(max-width:900px){.test-grid{grid-template-columns:1fr}}
.closing{background:linear-gradient(135deg,var(--emerald),var(--emerald-2));color:#fff;text-align:center}
.closing h2{font-family:var(--serif);font-weight:400;font-size:clamp(36px,5vw,64px);line-height:1.02;margin:12px 0 18px;color:#fff}
.closing h2 em{font-style:italic;color:var(--gold)}
.closing p{color:rgba(255,255,255,.82);max-width:52ch;margin:0 auto 30px;font-size:17px;line-height:1.65}
.closing .actions{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}
.closing .btn-ghost{color:#fff;border-color:rgba(255,255,255,.4)}
.closing .btn-ghost:hover{background:#fff;color:var(--emerald);border-color:#fff}
.closing .btn-gold:hover{background:#fff;color:var(--emerald)}
.ceo-quote{background:var(--ink);color:#fff;text-align:center}
.ceo-quote .q{font-family:var(--serif);font-style:italic;font-size:clamp(24px,3vw,34px);line-height:1.35;max-width:24ch;margin:0 auto}
.ceo-quote .q em{color:var(--gold)}
.ceo-quote .by{font-family:var(--mono);font-size:11px;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.6);margin-top:20px}
</style>
@php
    $ld = [
        '@context' => 'https://schema.org', '@type' => 'RealEstateAgent',
        'name' => config('cream.legal_name'), 'url' => url('/'),
        'image' => url('/assets/royal-manhattan.png'),
        'telephone' => config('cream.phone.display'), 'email' => config('cream.email'),
        'areaServed' => 'Greater Accra, Ghana',
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => 'Bawuah Junction, East Legon', 'addressLocality' => 'Accra', 'addressRegion' => 'Greater Accra', 'addressCountry' => 'GH'],
        'parentOrganization' => ['@type' => 'Organization', 'name' => config('cream.parent')],
        'sameAs' => [config('cream.social.instagram.url'), config('cream.social.tiktok.url'), config('cream.social.youtube.url'), config('cream.social.facebook.url')],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<section class="hero">
    <div class="bg" id="heroBg">
        <div class="slide is-active" style="background-image:url('/assets/royal-manhattan.png')"></div>
        <div class="slide" style="background-image:url('/assets/beverly-hills.jpeg')"></div>
        <div class="slide" style="background-image:url('/assets/salem-plus-home.JPG')"></div>
    </div>
    <div class="scrim"></div>
    <div class="container meta-top"><span class="badge"><span class="dot"></span>Credence Holdings Global · Real Estate Division</span></div>
    <div class="container content">
        <h1>Building Value.<br/><em>Creating Legacy.</em></h1>
        <p class="sub">Premium real estate, construction, and asset management for Ghanaians at home and in the diaspora — end-to-end, transparent, and built to outlive trends.</p>
        <div class="cta-row">
            <a href="/properties" class="btn-solid">See our properties <x-icon name="arrow"/></a>
            <a href="/contact" class="btn-ghost">Talk to us</a>
            <a href="{{ $wa }}" target="_blank" rel="noopener" class="wa-link"><x-icon name="wa"/> WhatsApp us</a>
        </div>
    </div>
    <div class="dots" id="heroDots"><span class="d on"></span><span class="d"></span><span class="d"></span></div>
</section>

<section class="feat sec">
    <div class="container">
        <div class="head">
            <div><div class="eyebrow"><span class="dot"></span>Featured Properties</div><h2>Homes, land &amp; new<br/>projects across <em>Accra</em>.</h2></div>
            <a href="/properties" class="btn-ghost">View all properties <x-icon name="arrow"/></a>
        </div>
        <div class="cr-grid">
            @foreach ($featured as $asset)
                <x-listing-card :asset="$asset"/>
            @endforeach
        </div>
        <div class="links">
            <a href="/properties/land" class="btn-ghost"><x-icon name="pin"/> Title-verified land</a>
            <a href="/new-projects" class="btn-ghost">New projects &amp; off-plan <x-icon name="arrow"/></a>
        </div>
    </div>
</section>

<section class="svcs sec">
    <div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Our Services</div><h2 class="section-title">Six disciplines,<br/>one trusted brand.</h2></div><div class="right">From foundation to finishing, acquisition to management — Credence handles every stage of the real estate lifecycle under one roof.</div></div>
        <div class="svc-grid">
            @foreach ($services as $i => $svc)
                <a class="svc" href="/contact?reason={{ $svc[0] }}">
                    <span class="num">0{{ $i + 1 }}</span>
                    <h3>{{ $svc[1] }}</h3>
                    <p>{{ $svc[2] }}</p>
                    <span class="enq">Enquire <x-icon name="arrow"/></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="why sec">
    <div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Why Credence</div><h2 class="section-title">Four reasons the<br/>diaspora trust us.</h2></div><div class="right">Measurable, observable commitments — not slogans. The proof arrives in your inbox on our timeline.</div></div>
        <div class="why-grid">
            <div class="why-item"><div class="n">01</div><div class="t">Title-searched land</div><div class="d">Every plot is searched at the Lands Commission and independently surveyed before we list it.</div></div>
            <div class="why-item"><div class="n">02</div><div class="t">Weekly site reports</div><div class="d">Building with us? You get weekly progress reports and photographic evidence throughout.</div></div>
            <div class="why-item"><div class="n">03</div><div class="t">One account manager</div><div class="d">A single point of contact who knows your property by name, from brief to handover and beyond.</div></div>
            <div class="why-item"><div class="n">04</div><div class="t">UK &amp; US time zones</div><div class="d">Zoom or WhatsApp calls scheduled across GMT, BST, EST and PST — we meet you where you are.</div></div>
        </div>
    </div>
</section>

<section class="reels sec">
    <div class="container">
        <div class="head">
            <div><div class="eyebrow"><span class="dot"></span>Walk-throughs</div><h2>Walk-throughs &amp; <em>behind-the-build</em>.</h2></div>
            <a href="{{ config('cream.social.instagram.url') }}" target="_blank" rel="noopener" class="btn-ghost">{{ config('cream.social.instagram.handle') }} <x-icon name="arrow"/></a>
        </div>
        <div class="reels-row">
            @foreach ($reels as $i => $r)
                @php
                    $cover = $r->cover();
                    $rTitleHtml = preg_replace('/\s(\S+)$/u', ' <em>$1</em>', e($r->title));
                    $rStatus = $r->is_exclusive ? 'Exclusive' : ($r->category === AssetCategory::Rent ? 'For Rent' : ($r->category === AssetCategory::ShortStay ? 'Short Stay' : 'For Sale'));
                    $area = trim(explode('·', (string) $r->location)[0]);
                @endphp
                <a href="/listings/{{ $r->slug }}" class="reel" data-video="{{ $r->video_url ? '/'.ltrim($r->video_url, '/') : '' }}">
                    @if ($r->video_url)
                        <video muted loop playsinline preload="metadata" @if ($cover) poster="{{ $cover->url() }}" @endif><source src="/{{ ltrim($r->video_url, '/') }}" type="video/mp4"/></video>
                    @elseif ($cover)
                        <img src="{{ $cover->url() }}" alt="{{ $r->title }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0" loading="lazy"/>
                    @else
                        <x-placeholder :label="$r->title"/>
                    @endif
                    <div class="top-row"><span class="tag{{ $i === 0 ? ' live' : '' }}">{{ $i === 0 ? '● ' : '' }}Walk-through</span><span class="dur">New</span></div>
                    <div class="play-ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
                    <div class="body">
                        <div class="ttl">{!! $rTitleHtml !!}</div>
                        <div class="meta"><span>{{ $area }}</span><span>{{ $rStatus }}</span></div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="ceo-quote sec">
    <div class="container">
        <p class="q">Growth without structure is noise. Growth with <em>governance is legacy.</em></p>
        <div class="by">Adeline Quarshie · CEO · <a href="/about" style="color:var(--gold)">Read the full message</a></div>
    </div>
</section>

@if ($testimonials->isNotEmpty())
<section class="test sec">
    <div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Client Voices</div><h2 class="section-title">Trusted at home<br/>and abroad.</h2></div><div class="right">A small selection of the families and investors we have quietly helped build, buy, and manage property across Accra and beyond.</div></div>
        <div class="test-grid">
            @foreach ($testimonials as $t)
                <div class="qcard"><span class="mark">&ldquo;</span><div class="q">{{ $t->body }}</div><div class="stars" aria-label="{{ $t->rating }} out of 5">{{ str_repeat('★', $t->rating).str_repeat('☆', 5 - $t->rating) }}</div><div class="who"><div class="av">@if ($t->photoUrl())<img src="{{ $t->photoUrl() }}" alt="{{ $t->name }}" loading="lazy"/>@else{{ $t->initial() }}@endif</div><div><div class="n">{{ $t->name }}</div>@if ($t->role)<div class="r">{{ $t->role }}</div>@endif</div></div></div>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:28px"><a class="btn-ghost" href="/testimonials">Read more client stories <x-icon name="arrow"/></a></div>
    </div>
</section>
@endif

<section class="closing sec">
    <div class="container">
        <div class="eyebrow" style="color:rgba(255,255,255,.7);justify-content:center"><span class="dot"></span>Begin the Conversation</div>
        <h2>Ready to build, buy,<br/><em>invest, or manage?</em></h2>
        <p>Partner with Credence — the discipline of a global firm with the local knowledge of an Accra-born team.</p>
        <div class="actions">
            <a class="btn-gold" href="/contact">Book a consultation <x-icon name="arrow"/></a>
            <a class="btn-ghost" href="{{ $wa }}" target="_blank" rel="noopener"><x-icon name="wa"/> WhatsApp</a>
            <a class="btn-ghost" href="tel:{{ config('cream.phone.tel') }}"><x-icon name="phone"/> Call {{ config('cream.phone.display') }}</a>
        </div>
    </div>
</section>
@endsection
