@extends('public.layout')

@php
    $services = [
        ['id' => 'development', 'title' => 'Real Estate<br/><em>Development.</em>', 'img' => '/assets/beverly-hills.jpeg', 'tag' => 'Case Study', 'alt' => 'Beverly Homes', 'reverse' => false, 'dark' => false, 'fromPrice' => false,
            'p' => ['We develop modern residential and commercial properties that combine luxury, functionality, and long-term value. From greenfield enclaves to single-lot homes, every project is led by an owner-operator team that stays accountable from concept to handover.', 'Our developments are designed around how people actually live — open ground floors, ensuite bedrooms, private outdoor rooms — and built to specifications you can audit at every stage.'],
            'del' => ['Concept design + brief development', 'Planning consent & permit liaison', 'Build-cost modelling & reserves', 'Marketing & sales programme', 'Handover & aftercare', 'HOA & estate management setup']],
        ['id' => 'sales', 'title' => 'Property Sales<br/><em>& Investment.</em>', 'img' => '/assets/royal-manhattan.png', 'tag' => 'Diaspora Programme', 'alt' => 'Royal Manhattan', 'reverse' => true, 'dark' => false, 'fromPrice' => false,
            'p' => ['Whether you are purchasing your dream home, buying land, or investing in income-generating properties — we guide you through every step with professionalism and transparency.', 'Our advisory team verifies title, models yields, and negotiates on your behalf. For diaspora buyers we offer video walkthroughs, escrow coordination, and a single point of contact across UK, US, and Ghana time zones.'],
            'del' => ['Curated listing access', 'Title & document verification', 'Yield & appreciation modelling', 'Negotiation & closing', 'Video walk-throughs (diaspora)', 'Escrow & settlement coordination']],
        ['id' => 'management', 'title' => 'Property<br/><em>Management.</em>', 'img' => '', 'tag' => 'Live Service', 'alt' => 'Managed home', 'reverse' => false, 'dark' => false, 'fromPrice' => true,
            'p' => ['We manage residential and commercial properties with efficiency and care — ensuring properties remain profitable, secure, and well-maintained, while owners enjoy clarity over income, occupancy, and condition.', 'Whether you own one home or a small portfolio, you receive monthly statements, photographic condition reports, and a single account manager who knows your property by name.'],
            'del' => ['Tenant sourcing & vetting', 'Rent collection & reconciliation', '24-hour maintenance response', 'Monthly owner statements', 'Quarterly condition reports', 'HOA & estate fees handling']],
        ['id' => 'construction', 'title' => 'Construction &<br/><em>Project Management.</em>', 'img' => '', 'tag' => 'On Site', 'alt' => 'Construction site', 'reverse' => true, 'dark' => true, 'fromPrice' => false,
            'p' => ['From foundation to finishing, we oversee construction projects with attention to detail, quality control, and timely delivery. Weekly site reports, audit trails on every variation, and a project manager you can reach by name.', 'For diaspora clients, our reporting is built to give you the same line-of-sight as if you walked the site each Saturday yourself.'],
            'del' => ['Schedule & cost discipline', 'Trade procurement & vetting', 'Weekly progress reports', 'QA walk-throughs & snag lists', 'Variation order audit trail', 'Handover & defects period']],
        ['id' => 'interiors', 'title' => 'Interior Decor<br/><em>& Styling.</em>', 'img' => '/assets/interior-decor.jpg', 'tag' => 'Credence Interiors', 'alt' => 'Styled interior', 'reverse' => false, 'dark' => false, 'fromPrice' => false,
            'p' => ['Through premium interior solutions, we transform spaces into elegant and functional environments that reflect style and sophistication. From full furnishings on a turn-key build to targeted re-styling of a single room.', 'Delivered by Credence Interiors, our in-house specialist arm — for clients who want one team responsible from drywall to drapes.'],
            'del' => ['Concept boards & brief', 'FF&E specification & procurement', 'Bespoke joinery & soft goods', 'Art & accessories curation', 'Install & styling day', 'Photography & reveal']],
        ['id' => 'land', 'title' => 'Land Acquisition<br/><em>& Due Diligence.</em>', 'img' => '', 'tag' => 'Due Diligence', 'alt' => 'Land', 'reverse' => true, 'dark' => false, 'fromPrice' => true,
            'p' => ['We assist clients with land verification, documentation checks, survey coordination, and legal guidance to ensure safe and secure property transactions. The single most important step we take for diaspora clients.', 'Every plot we present has been verified through the Lands Commission, surveyed independently, and signed off by our retained counsel — no exceptions.'],
            'del' => ['Title search at Lands Commission', 'Independent boundary survey', 'Chief / family verification', 'Legal counsel sign-off', 'Indenture & registration', 'Ongoing site monitoring']],
    ];
@endphp

@push('head')
<style>
.svc-block{padding:clamp(64px,8vw,120px) 0;border-bottom:1px solid var(--line);scroll-margin-top:120px}
.svc-block:nth-child(odd){background:var(--bone)}.svc-block:nth-child(even){background:var(--bone-2)}
.svc-block.dark{background:#0F0E0C;color:#fff;border-bottom-color:rgba(255,255,255,.1)}
.svc-block.dark p,.svc-block.dark .eyebrow{color:rgba(255,255,255,.8)}
.svc-grid{display:grid;grid-template-columns:1fr 1.1fr;gap:70px;align-items:center}
.svc-block.reverse .svc-grid .info{order:2}.svc-block.reverse .svc-grid .imgblk{order:1}
.svc-grid .info h2{font-family:var(--serif);font-weight:400;font-size:clamp(34px,4vw,60px);line-height:1.02;letter-spacing:-0.015em;margin:14px 0 20px}
.svc-grid .info h2 em{font-style:italic;color:var(--gold)}
.svc-grid .info p{font-size:16.5px;line-height:1.7;color:var(--ink-2);margin:0 0 16px}
.deliverables{margin-top:26px;display:grid;grid-template-columns:1fr 1fr;gap:12px 26px}
.deliverables .d{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--ink-2);line-height:1.45}
.deliverables .d .tick{width:18px;height:18px;border-radius:50%;background:var(--emerald);color:#fff;display:grid;place-items:center;flex:none;margin-top:1px}
.svc-block.dark .deliverables .d{color:rgba(255,255,255,.82)}.svc-block.dark .deliverables .d .tick{background:var(--gold);color:var(--ink)}
.imgblk{position:relative;aspect-ratio:4/5;border-radius:var(--r-l);overflow:hidden;background:var(--emerald-2);box-shadow:var(--shadow-3)}
.imgblk img{width:100%;height:100%;object-fit:cover}
.imgblk .tag{position:absolute;top:18px;left:18px;background:rgba(255,255,255,.92);backdrop-filter:blur(8px);padding:8px 14px;border-radius:999px;font-family:var(--mono);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink);z-index:2}
@media(max-width:1080px){.svc-grid,.svc-block.reverse .svc-grid{grid-template-columns:1fr;gap:36px}.svc-block.reverse .svc-grid .info{order:2}.svc-block.reverse .svc-grid .imgblk{order:1}.imgblk{aspect-ratio:4/3}}
@media(max-width:720px){.deliverables{grid-template-columns:1fr}}
.jumpbar .inner{display:flex;gap:8px;flex-wrap:wrap}
</style>
@endpush

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Services']]"
        h1="Six disciplines,<br/><em>one trusted brand.</em>"
        sub="From foundation to finishing, acquisition to management — Credence handles every stage of the real estate lifecycle under one roof."/>

    <section class="jumpbar"><div class="inner">
        @foreach ($services as $s)<a href="#{{ $s['id'] }}">{{ trim(str_replace('.', '', strip_tags($s['title']))) }}</a>@endforeach
    </div></section>

    @foreach ($services as $s)
        <section class="svc-block{{ $s['dark'] ? ' dark' : '' }}{{ $s['reverse'] ? ' reverse' : '' }}" id="{{ $s['id'] }}">
            <div class="container"><div class="svc-grid">
                @if ($s['reverse'])
                    <div class="imgblk"><span class="tag">{{ $s['tag'] }}</span>@if ($s['img'])<img src="{{ $s['img'] }}" alt="{{ $s['alt'] }}"/>@else<x-placeholder :label="$s['alt']"/>@endif</div>
                @endif
                <div class="info">
                    <div class="eyebrow"><span class="dot"></span>Service</div>
                    <h2>{!! $s['title'] !!}</h2>
                    @foreach ($s['p'] as $para)<p>{{ $para }}</p>@endforeach
                    @if ($s['fromPrice'])<p style="font-family:var(--mono);font-size:12px;letter-spacing:.1em;color:var(--gold-2)">From GHS ___ · {{-- TODO(sam): add a real "from" price --}}price on request</p>@endif
                    <div class="deliverables">@foreach ($s['del'] as $d)<div class="d"><span class="tick"><x-icon name="check"/></span>{{ $d }}</div>@endforeach</div>
                    <a class="btn-gold" style="margin-top:28px" href="/contact?reason={{ $s['id'] }}">Enquire about this service <x-icon name="arrow"/></a>
                </div>
                @if (! $s['reverse'])
                    <div class="imgblk"><span class="tag">{{ $s['tag'] }}</span>@if ($s['img'])<img src="{{ $s['img'] }}" alt="{{ $s['alt'] }}"/>@else<x-placeholder :label="$s['alt']"/>@endif</div>
                @endif
            </div></div>
        </section>
    @endforeach

    <section class="sec ink" style="text-align:center"><div class="container" style="max-width:760px">
        <div class="eyebrow" style="color:rgba(255,255,255,.7);justify-content:center"><span class="dot"></span>Begin the Conversation</div>
        <h2 class="t" style="color:#fff;margin:14px 0 18px">Ready to build, buy,<br/><em>invest, or manage?</em></h2>
        <p class="prose" style="margin:0 auto 28px;color:rgba(255,255,255,.75)">Partner with Credence — the discipline of a global firm with the local knowledge of an Accra-born team.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap"><a class="btn-gold" href="/contact">Book a consultation <x-icon name="arrow"/></a><a class="btn-ghost" style="color:#fff;border-color:rgba(255,255,255,.3)" href="/properties">Browse properties</a></div>
    </div></section>
@endsection
