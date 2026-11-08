@extends('public.layout')

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Insights', 'href' => '/insights'], ['label' => 'Blog']]"
        h1="The Credence<br/><em>blog.</em>"
        sub="Perspectives on Ghana real estate, property management and diaspora investment."/>
    <section class="sec bone2"><div class="container">
        @if ($articles->isEmpty())
            <div class="media-grid">
                <a class="media-card" href="{{ config('cream.show.channel') }}" target="_blank" rel="noopener"><div class="mc-top"><x-placeholder label="Signature Home Show"/></div><div class="mc-body"><div class="mc-k">Video</div><div class="mc-t">The Signature Home Show</div><div class="mc-d">Watch our episodes on YouTube.</div></div></a>
            </div>
            <p class="small" style="margin-top:18px">New articles are on the way.</p>
        @else
            <div class="media-grid">
                @foreach ($articles as $a)
                    <a class="media-card" href="{{ $a->url() }}">
                        <div class="mc-top">@if ($a->coverUrl())<img src="{{ $a->coverUrl() }}" alt="{{ $a->title }}"/>@else<x-placeholder label="Credence Blog"/>@endif</div>
                        <div class="mc-body"><div class="mc-k">Article</div><div class="mc-t">{{ $a->title }}</div><div class="mc-d">{{ $a->excerpt }}</div><div class="mc-meta"><span>{{ $a->published_at?->format('d M Y') }}</span><span>Read <x-icon name="arrow"/></span></div></div>
                    </a>
                @endforeach
            </div>
            <div style="margin-top:24px">{{ $articles->links() }}</div>
        @endif
    </div></section>
@endsection
