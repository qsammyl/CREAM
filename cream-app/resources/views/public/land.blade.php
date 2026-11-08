@extends('public.layout')

@push('head')
<style>
.land-row{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.lcard{border:1px solid var(--line);border-radius:var(--r-l);padding:30px 26px;display:flex;flex-direction:column;gap:12px;background:var(--paper)}
.lcard .ltag{font-family:var(--mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-2)}
.lcard h3{font-family:var(--serif);font-weight:400;font-size:27px;line-height:1.1;margin:0;letter-spacing:-0.01em}
.lcard .larea{font-family:var(--mono);font-size:12px;letter-spacing:.04em;color:var(--ink-3)}
.lcard .lspecs{display:flex;flex-direction:column;gap:6px;margin-top:6px;font-size:14px;color:var(--ink-2)}
.lcard .lrow{display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--line)}
.lcard .lrow:last-child{border-bottom:0}
.lcard .lprice{font-family:var(--serif);font-size:30px;margin-top:8px}
.lcard .lactions{display:flex;gap:10px;margin-top:8px;flex-wrap:wrap}
.lcard .lactions a{flex:1;justify-content:center}
@media(max-width:1080px){.land-row{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    <x-page-hero
        :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Properties', 'href' => '/properties'], ['label' => 'Land']]"
        h1="Title-verified<br/><em>plots.</em>"
        sub="Every plot we present has been searched at the Lands Commission, independently surveyed, and signed off by our retained counsel — documentation available on request."/>

    <section class="sec bone2">
        <div class="container">
            <div style="margin-bottom:28px" class="eyebrow"><span class="dot"></span>{{ $plots->count() }} plots · documentation on file</div>
            <div class="land-row">
                @foreach ($plots as $l)
                    <article class="lcard">
                        <span class="ltag">Plot · {{ trim(explode('·', (string) $l->location)[0]) }}</span>
                        <h3>{{ $l->title }}</h3>
                        <div class="larea">{{ $l->plot_size }} · Title-verified</div>
                        <div class="lspecs">
                            @if ($l->tenure)<div class="lrow"><span>Tenure</span><span>{{ $l->tenure }}</span></div>@endif
                            @if ($l->survey)<div class="lrow"><span>Survey</span><span>{{ $l->survey }}</span></div>@endif
                            @if ($l->access)<div class="lrow"><span>Access</span><span>{{ $l->access }}</span></div>@endif
                        </div>
                        <div class="lprice"><x-money :asset="$l"/></div>
                        <div class="lactions">
                            <a class="btn-ghost" href="/listings/{{ $l->slug }}">Details</a>
                            <a class="btn-gold" href="/contact?reason=land">Request documents <x-icon name="arrow"/></a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="sec bone"><div class="container" style="text-align:center">
        <h2 class="t" style="margin:0 auto 14px;max-width:18ch">Buying land in Ghana? <em>Verify first.</em></h2>
        <p class="prose" style="margin:0 auto 24px;color:var(--ink-2)">An official land search at the Lands Commission is the single most important step before you pay for any plot. We run it for you.</p>
        <a href="/search-document" class="btn-solid">Our land search service <x-icon name="arrow"/></a>
    </div></section>
@endsection
