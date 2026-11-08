@props(['asset'])
@php
    use App\Enums\AssetCategory;
    use App\Enums\AssetStatus;

    $l = $asset;
    $href = '/listings/'.$l->slug;
    $cover = $l->cover();
    $isSold = in_array($l->status, [AssetStatus::Sold, AssetStatus::Let], true);

    $catLabel = match ($l->category) {
        AssetCategory::Sale => 'For Sale',
        AssetCategory::Rent => 'For Rent',
        AssetCategory::Land => 'Land',
        AssetCategory::ShortStay => 'Short Stay',
        AssetCategory::NewProject => 'Off-plan',
    };
    $catCls = $l->category === AssetCategory::Sale ? 'em'
        : (in_array($l->category, [AssetCategory::Rent, AssetCategory::Land, AssetCategory::NewProject], true) ? 'gold' : '');

    $tags = collect([$l->category->value]);
    $l->is_off_plan && $tags->push('under-construction');
    $l->is_exclusive && $tags->push('exclusive');
    $l->is_furnished && $tags->push('furnished');
    $isSold && $tags->push($l->status->value); // 'sold' | 'let'

    $titleHtml = preg_replace('/\s(\S+)$/u', ' <em>$1</em>', e($l->title));
    $statusBadge = $l->statusBadge();
@endphp
<article class="cr-card" data-tags="{{ $tags->implode(' ') }}" data-area="{{ $l->area_key }}" @if ($isSold) data-sold="1" @endif>
    <div class="cr-img">
        @if ($catLabel)<span class="cr-tag {{ $catCls }}">{{ $catLabel }}</span>@endif
        @if ($statusBadge)
            <span class="cr-tag" style="position:absolute;top:14px;right:14px;left:auto;z-index:3;background:var(--ink);color:#fff">{{ $statusBadge }}</span>
        @endif
        @if ($cover)
            <img src="{{ $cover->url() }}"
                 @if ($cover->urlSm()) srcset="{{ $cover->srcset() }}" sizes="(max-width:640px) 100vw, 400px" @endif
                 alt="{{ $cover->alt ?: $l->title.' — '.$l->location }}" loading="lazy"/>
        @else
            <x-placeholder :label="$l->title"/>
        @endif
    </div>
    <div class="cr-cbody">
        <div class="cr-price"><x-money :asset="$l"/></div>
        <div class="cr-ttl">{!! $titleHtml !!}</div>
        <div class="cr-loc"><x-icon name="pin"/>{{ $l->location }}</div>
        <div class="cr-ref">REF · {{ $l->reference }}</div>
        <div class="cr-stats">
            @if ($l->beds !== null)<span>{{ $l->beds }} <span class="dim">bd</span></span>@endif
            @if ($l->baths !== null)<span>{{ $l->baths }} <span class="dim">ba</span></span>@endif
            @if ($l->plot_size)<span>{{ $l->plot_size }}</span>@endif
            @if ($l->title_verified)<span class="cr-verified"><x-icon name="check"/> Title verified</span>@endif
        </div>
    </div>
    <a class="cr-cover" href="{{ $href }}" aria-label="{{ $l->title }}">{{ $l->title }}</a>
</article>
