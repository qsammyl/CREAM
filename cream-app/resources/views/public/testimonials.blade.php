@extends('public.layout')

@push('head')
<style>
.tst-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;margin-top:8px}
.tst-card{background:var(--paper);border:1px solid var(--line);border-radius:var(--r-l);padding:26px 24px;display:flex;flex-direction:column}
.tst-card .mark{font-family:var(--serif);font-size:44px;line-height:.6;color:var(--gold);height:22px}
.tst-card .q{color:var(--ink-2);line-height:1.7;font-size:16px;margin:6px 0 16px;flex:1}
.tst-card .stars{color:var(--gold);letter-spacing:2px;font-size:15px;margin-bottom:14px}
.tst-card .who{display:flex;align-items:center;gap:12px}
.tst-card .av{width:42px;height:42px;border-radius:50%;background:var(--emerald);color:#fff;display:grid;place-items:center;font-family:var(--serif);font-size:19px;flex:none;overflow:hidden}
.tst-card .av img{width:100%;height:100%;object-fit:cover;object-position:center 22%}
.tst-card .n{font-family:var(--serif);font-size:18px}
.tst-card .r{font-family:var(--mono);font-size:10.5px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-3);margin-top:2px}
.tst-cta{background:var(--ink);color:#fff;border-radius:var(--r-l);padding:36px;text-align:center;margin-top:34px}
.tst-cta h3{font-family:var(--serif);font-weight:400;font-size:28px;margin:0 0 10px;color:#fff}
.tst-cta p{color:rgba(255,255,255,.7);max-width:46ch;margin:0 auto 18px}
.tst-empty{text-align:center;color:var(--ink-3);padding:30px 0}
</style>
@endpush

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Client stories']]"
        h1="Client<br/><em>stories.</em>"
        sub="The families and investors we have helped build, buy, rent and manage property across Accra and beyond — in their own words."/>

    <section class="sec bone"><div class="container">
        @if ($testimonials->isEmpty())
            <p class="tst-empty">Our first published stories will appear here soon.</p>
        @else
            <div class="tst-grid">
                @foreach ($testimonials as $t)
                    <figure class="tst-card">
                        <span class="mark" aria-hidden="true">&ldquo;</span>
                        <blockquote class="q">{{ $t->body }}</blockquote>
                        <div class="stars" aria-label="{{ $t->rating }} out of 5 stars">{{ str_repeat('★', $t->rating).str_repeat('☆', 5 - $t->rating) }}</div>
                        <figcaption class="who">
                            <div class="av">@if ($t->photoUrl())<img src="{{ $t->photoUrl() }}" alt="{{ $t->name }}" loading="lazy"/>@else{{ $t->initial() }}@endif</div>
                            <div><div class="n">{{ $t->name }}</div>@if ($t->role)<div class="r">{{ $t->role }}</div>@endif</div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif

        <div class="tst-cta">
            <h3>Worked with us?</h3>
            <p>We would love to hear how it went. It takes a minute, and it helps the next family know what to expect.</p>
            <a class="btn-gold" href="/feedback">Share your experience <x-icon name="arrow"/></a>
        </div>
    </div></section>
@endsection
