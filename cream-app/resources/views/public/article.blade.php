@extends('public.layout')

@push('head')
<style>
.post{max-width:760px;margin:0 auto}
.post .meta{font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--ink-3);margin-bottom:14px}
.post .hero-img{width:100%;border-radius:var(--r-l);margin:0 0 28px;aspect-ratio:16/9;object-fit:cover}
.post .body p{font-size:17px;line-height:1.8;color:var(--ink-2);margin:0 0 20px}
.post .body h2{font-family:var(--serif);font-weight:400;font-size:26px;letter-spacing:-0.01em;color:var(--ink);margin:34px 0 14px}
.post .body h3{font-family:var(--serif);font-weight:400;font-size:22px;color:var(--ink);margin:28px 0 12px}
.post .body ul,.post .body ol{margin:0 0 20px;padding-left:22px;color:var(--ink-2);font-size:17px;line-height:1.8}
.post .body li{margin:0 0 8px}
.post .body a{color:var(--emerald-3);text-decoration:underline}
.post .body hr{border:0;border-top:1px solid var(--line);margin:28px 0}
.post .source{font-size:13px;color:var(--ink-3);margin:26px 0 0;font-style:italic}
.post .share{display:flex;gap:10px;margin:28px 0;padding-top:20px;border-top:1px solid var(--line);flex-wrap:wrap}
.post .cta{background:var(--ink);color:#fff;border-radius:var(--r-l);padding:32px;text-align:center;margin-top:36px}
.post .cta h3{font-family:var(--serif);font-weight:400;font-size:28px;margin:0 0 14px;color:#fff}
.comments{max-width:760px;margin:40px auto 0;padding-top:30px;border-top:1px solid var(--line)}
.comments h3{font-family:var(--serif);font-weight:400;font-size:26px;margin:0 0 20px}
.comment{padding:16px 0;border-bottom:1px solid var(--line)}
.comment .who{font-family:var(--serif);font-size:18px}
.comment .when{font-family:var(--mono);font-size:11px;letter-spacing:.08em;color:var(--ink-3);margin-left:8px}
.comment .ctext{color:var(--ink-2);line-height:1.65;margin-top:6px}
.cform{margin-top:28px;background:var(--paper);border:1px solid var(--line);border-radius:var(--r-l);padding:28px}
.cform .row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.cform label{display:block;font-family:var(--mono);font-size:10.5px;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-3);margin:0 0 6px}
.cform input,.cform textarea{width:100%;padding:11px 13px;border:1px solid var(--line-2);border-radius:var(--r-s);background:#fff;font:inherit}
.cform textarea{min-height:110px;resize:vertical;margin-bottom:12px}
.cform .hp{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden}
.c-ok{background:#F0FAF4;border:1px solid #B6DFC7;color:#1A5C38;padding:12px 15px;border-radius:var(--r-s);margin-bottom:16px}
.c-err{background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;padding:12px 15px;border-radius:var(--r-s);margin-bottom:16px}
@media(max-width:620px){.cform .row-2{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Blog', 'href' => '/blog'], ['label' => $article->title]]"
        :h1="e($article->title)"/>

    <section class="sec bone"><div class="container"><article class="post">
        <div class="meta">{{ $article->published_at?->format('d M Y') }} · By {{ $article->authorLabel() }}</div>
        @if ($article->coverUrl())<img class="hero-img" src="{{ $article->coverUrl() }}" alt="{{ $article->title }}"/>@endif
        <div class="body">{!! $article->body !!}</div>
        @if ($article->source)
            <p class="source">Originally published by
                @if ($article->source_url)<a href="{{ $article->source_url }}" target="_blank" rel="noopener">{{ $article->source }}</a>@else{{ $article->source }}@endif.
            </p>
        @endif
        <div class="share">
            <a class="btn-ghost" href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener">Share on WhatsApp</a>
            <a class="btn-ghost" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url($article->url())) }}" target="_blank" rel="noopener">Share on Facebook</a>
        </div>
        <div class="cta"><h3>Thinking about a move?</h3><a class="btn-gold" href="/contact">Talk to us <x-icon name="arrow"/></a></div>
    </article></div></section>

    @if ($article->allow_comments)
        <section class="sec bone2"><div class="container"><div class="comments" id="comments">
            <h3>Comments @if ($comments->count())<span style="font-family:var(--mono);font-size:14px;color:var(--ink-3)">· {{ $comments->count() }}</span>@endif</h3>

            @if (session('comment_status') === 'pending')
                <div class="c-ok">Thanks, your comment will appear once it's approved.</div>
            @endif

            @forelse ($comments as $c)
                <div class="comment">
                    <span class="who">{{ $c->name }}</span><span class="when">{{ $c->created_at->format('d M Y') }}</span>
                    <div class="ctext">{{ $c->body }}</div>
                </div>
            @empty
                <p class="muted" style="color:var(--ink-3)">Be the first to comment.</p>
            @endforelse

            <form class="cform" id="comment-form" method="POST" action="{{ route('comments.store', $article->slug) }}">
                @csrf
                @error('comment')<div class="c-err">{{ $message }}</div>@enderror
                <div class="row-2">
                    <div><label>Name</label><input name="name" value="{{ old('name') }}" required>@error('name')<div class="field-err" style="color:#991B1B;font-size:12.5px">{{ $message }}</div>@enderror</div>
                    <div><label>Email (not published)</label><input type="email" name="email" value="{{ old('email') }}" required>@error('email')<div class="field-err" style="color:#991B1B;font-size:12.5px">{{ $message }}</div>@enderror</div>
                </div>
                <label>Comment</label>
                <textarea name="body" required>{{ old('body') }}</textarea>@error('body')<div class="field-err" style="color:#991B1B;font-size:12.5px">{{ $message }}</div>@enderror
                <div class="hp" aria-hidden="true"><label>Website</label><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                @include('partials.turnstile')
                <button type="submit" class="btn-solid">Post comment <x-icon name="arrow"/></button>
                <p class="small" style="color:var(--ink-3);margin-top:10px">Comments are moderated and your email is never shown. See our <a href="/privacy">privacy notice</a>.</p>
            </form>
        </div></div></section>
    @endif
@endsection
