@props(['crumbs' => [], 'h1', 'sub' => null])
<section class="page-hero img">
    <div class="container">
        <div class="crumbs">
            @foreach ($crumbs as $i => $c)
                @if (! empty($c['href']))<a href="{{ $c['href'] }}">{{ $c['label'] }}</a>@else<span>{{ $c['label'] }}</span>@endif
                @if ($i < count($crumbs) - 1)<span class="sep">·</span>@endif
            @endforeach
        </div>
        <h1>{!! $h1 !!}</h1>
        @if ($sub)<p class="sub">{!! $sub !!}</p>@endif
    </div>
</section>
