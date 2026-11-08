@extends('public.layout')

@section('content')
    <x-page-hero :crumbs="$crumbs" :h1="$h1" :sub="$sub"/>

    <section class="sec bone2">
        <div class="container">
            <div style="margin-bottom:28px" class="eyebrow"><span class="dot"></span>{{ $eyebrow }}</div>
            @if ($items->isEmpty() && ! empty($emptyHtml ?? null))
                <div class="cr-empty"><h3>Nothing listed right now</h3><p>{!! $emptyHtml !!}</p></div>
            @else
                <div class="cr-grid">
                    @foreach ($items as $a)
                        <x-listing-card :asset="$a"/>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if ($offMarket ?? false)
        @include('public.partials.off-market')
    @endif
@endsection
