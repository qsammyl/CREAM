@extends('public.layout')

@push('head')
<style>
.np{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;margin-bottom:48px}
.np-img{position:relative;aspect-ratio:4/3;border-radius:var(--r-l);overflow:hidden;background:var(--emerald-2)}
.np-img img{width:100%;height:100%;object-fit:cover}
.np-body h2{margin:10px 0 14px}
.np-meta{display:flex;flex-wrap:wrap;gap:8px 22px;margin-bottom:16px;font-size:14px;color:var(--ink-2)}
.np-meta b{font-family:var(--mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-2);display:block}
.np-body p{color:var(--ink-2);line-height:1.7;margin:0 0 18px}
@media(max-width:900px){.np{grid-template-columns:1fr;gap:24px}}
</style>
@endpush

@section('content')
    <x-page-hero
        :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'New projects']]"
        h1="New projects<br/>&amp; <em>off-plan.</em>"
        sub="Reserve early at introductory off-plan pricing, with weekly site updates through to handover."/>

    <section class="sec bone2"><div class="container">
        @forelse ($items as $l)
            @php($cover = $l->cover())
            <article class="np">
                <div class="np-img">
                    @if ($cover)<img src="{{ $cover->url() }}" alt="{{ $l->title }}" loading="lazy"/>@else<x-placeholder :label="$l->title"/>@endif
                </div>
                <div class="np-body">
                    <div class="eyebrow"><span class="dot"></span>{{ $l->stage ?: 'Under construction' }}</div>
                    <h2 class="t">{!! preg_replace('/\s(\S+)$/u', ' <em>$1</em>', e($l->title)) !!}</h2>
                    <div class="np-meta">
                        <span><b>Stage</b> {{ $l->stage ?: 'Under construction' }}</span>
                        <span><b>Delivery</b> {{ $l->expected_delivery ?: 'TBC' }}</span>
                        <span><b>From</b> {{ $l->isPriceOnRequest() ? 'Price on request' : $l->formattedPrice() }}</span>
                        <span><b>Beds</b> {{ $l->beds }}</span>
                    </div>
                    <p>{{ $l->summary }}</p>
                    <div style="display:flex;gap:10px;flex-wrap:wrap"><a class="btn-ghost" href="/listings/{{ $l->slug }}">View project</a><a class="btn-solid" href="#register">Register interest <x-icon name="arrow"/></a></div>
                </div>
            </article>
        @empty
            <div class="cr-empty"><h3>No new projects open right now</h3><p>Register your interest below to hear first.</p></div>
        @endforelse
    </div></section>

    <section class="sec bone" id="register"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Register interest</div><h2 class="t">Get first access to <em>off-plan</em> prices.</h2></div><div class="right">Register and we will send pricing, floor plans and site updates as the project progresses.</div></div>
        @include('public.partials.form-status')
        <form class="cr-form" style="max-width:720px" action="{{ route('enquiries.store') }}" method="POST" data-enquiry>
            @csrf
            <input type="hidden" name="type" value="new_project"/>
            <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
            <div class="row-2"><div><label>Full name</label><input name="name" required/></div><div><label>Project</label><select name="project">@foreach ($items as $l)<option>{{ $l->title }}</option>@endforeach<option>Not sure yet</option></select></div></div>
            <div class="row-2"><div><label>Email</label><input type="email" name="email" required/></div><div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div></div>
            <div><label>Message</label><textarea name="message" placeholder="Unit preference, budget, timeline…"></textarea></div>
            @include('partials.turnstile')
            <button type="submit" class="submit">Register interest <x-icon name="arrow"/></button>
            <div class="form-msg" style="display:none"></div>
        </form>
    </div></section>
@endsection
