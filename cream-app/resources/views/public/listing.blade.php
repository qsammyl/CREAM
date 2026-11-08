@extends('public.layout')

@php
    use App\Enums\AssetCategory;

    $l = $asset;
    $isLand = $l->isLand();
    $media = $l->media;
    $cover = $l->cover();
    $statusLabel = match ($l->category) {
        AssetCategory::Sale => 'For Sale', AssetCategory::Rent => 'For Rent',
        AssetCategory::Land => 'Land', AssetCategory::ShortStay => 'Short Stay',
        AssetCategory::NewProject => 'Off-plan',
    };
    $statusCls = match ($l->category) {
        AssetCategory::Sale => 'sale', AssetCategory::Rent => 'rent',
        AssetCategory::Land => 'land', default => 'offplan',
    };
    $locShort = trim(explode('·', (string) $l->location)[0]);

    $specRows = [];
    if ($l->beds !== null) $specRows[] = ['Bedrooms', $l->beds];
    if ($l->baths !== null) $specRows[] = ['Bathrooms', $l->baths];
    if ($l->floor_area) $specRows[] = ['Floor area', $l->floor_area];
    if ($l->plot_size) $specRows[] = ['Plot size', $l->plot_size];
    if ($l->tenure) $specRows[] = ['Tenure', $l->tenure];
    if ($l->survey) $specRows[] = ['Survey', $l->survey];
    if ($l->access) $specRows[] = ['Access', $l->access];
    if ($l->expected_delivery) $specRows[] = ['Expected delivery', $l->expected_delivery];
    $specRows[] = ['Location', $l->location];
    $specRows[] = ['Reference', $l->reference];

    $waMsg = "Hi Credence, I'm interested in {$l->title} (Ref {$l->reference}). Please share more details.";
    $waHref = 'https://wa.me/'.config('cream.whatsapp.number').'?text='.rawurlencode($waMsg);
    $mapSrc = 'https://maps.google.com/maps?q='.rawurlencode(($l->location ?: '').', Accra, Ghana').'&t=&ie=UTF8&output=embed';
    $titleHtml = preg_replace('/\s(\S+)$/u', ' <em>$1</em>', e($l->title));
@endphp

@push('head')
@php
    $ld = array_filter([
        '@context' => 'https://schema.org', '@type' => 'RealEstateListing',
        'name' => $l->title, 'url' => url('/listings/'.$l->slug),
        'description' => $l->summary ?: '',
        'image' => $cover?->url(),
        'offers' => $l->price ? ['@type' => 'Offer', 'price' => $l->price, 'priceCurrency' => $l->currency->value, 'availability' => 'https://schema.org/InStock'] : null,
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => $l->location, 'addressRegion' => 'Greater Accra', 'addressCountry' => 'GH'],
        'numberOfBedrooms' => (! $isLand && $l->beds) ? $l->beds : null,
    ], fn ($v) => $v !== null && $v !== '');
@endphp
<script type="application/ld+json">{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<section class="prop-hero">
    @if ($cover)
        <div class="bg" style="background-image:url('{{ $cover->url() }}')"></div>
    @else
        <div class="bg"><x-placeholder :label="$l->title"/></div>
    @endif
    <div class="scrim"></div>
    <div class="content container">
        <div class="crumbs"><a href="/">Credence</a><span class="sep">/</span><a href="/properties">Properties</a><span class="sep">/</span>{{ $l->title }}</div>
        <div class="badge-row">
            <span class="status {{ $statusCls }}">{{ $statusLabel }}</span>
            @if ($l->statusBadge())<span class="status offplan">{{ $l->statusBadge() }}</span>@endif
            @if ($l->title_verified)<span class="verified"><x-icon name="check"/> Title verified</span>@endif
            <span class="ref-pill">REF · {{ $l->reference }}</span>
        </div>
        <h1>{!! $titleHtml !!}</h1>
        <div class="addr"><x-icon name="pin"/> {{ $l->location }} · Ghana</div>
    </div>
</section>

<section class="prop-main">
    <div class="container">
        <a class="back-link" href="/properties"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M19 12H5M11 6l-6 6 6 6"/></svg> Back to Properties</a>
        <div class="prop-layout">
            <div>
                <div class="prop-price"><x-money :asset="$l"/></div>
                <div class="prop-price-sub">{{ $l->price_note }}</div>

                <div class="gallery">
                    @if ($media->count())
                        @foreach ($media as $i => $m)
                            <figure class="{{ $i === 0 ? 'g0' : '' }}"><img src="{{ $m->url() }}" alt="{{ $m->alt ?: $l->title.' — photo '.($i + 1) }}" loading="lazy" data-full="{{ $m->url() }}"/></figure>
                        @endforeach
                    @else
                        <figure class="g0" style="cursor:default"><x-placeholder :label="$l->title"/></figure>
                    @endif
                </div>

                @if ($l->video_url)
                    <div class="vid-wrap"><video controls preload="none" @if ($cover) poster="{{ $cover->url() }}" @endif><source src="/{{ ltrim($l->video_url, '/') }}" type="video/mp4"/>Your browser does not support the video tag.</video></div>
                @endif

                <div class="prop-section">
                    <h3>About this property</h3>
                    @if ($l->summary)<p>{{ $l->summary }}</p>@endif
                    @if ($l->summary2)<p>{{ $l->summary2 }}</p>@endif
                    @if ($l->description)<div>{!! nl2br(e($l->description)) !!}</div>@endif
                    @if ($l->availability)<p><strong>Availability:</strong> {{ $l->availability }}.</p>@endif
                </div>

                @if ($l->features)
                    <div class="prop-section"><h3>{{ $isLand ? 'Plot details' : 'Key features' }}</h3><div class="features-grid">
                        @foreach ($l->features as $f)<div class="feature-item"><span class="fdot"></span>{{ $f }}</div>@endforeach
                    </div></div>
                @endif

                @if ($l->title_verified && $l->verified_summary)
                    <div class="prop-section"><h3>Title verified</h3><p>{{ $l->verified_summary }}</p></div>
                @endif

                @if ($l->documents_available)
                    <div class="prop-section"><h3>Documents available on request</h3><ul class="doc-list">
                        @foreach ($l->documents_available as $d)<li><x-icon name="check"/> {{ $d }}</li>@endforeach
                    </ul></div>
                @endif

                <div class="prop-section"><h3>Location</h3><p>Approximate location shown by area, not exact address. <div class="map-embed"><iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="{{ $l->title }} — {{ $l->location }}" src="{{ $mapSrc }}"></iframe></div></p></div>

                {{-- Spec table (kept for completeness alongside the narrative) --}}
                <div class="prop-section"><h3>At a glance</h3><ul class="doc-list">
                    @foreach ($specRows as $row)<li><strong>{{ $row[0] }}:</strong> {{ $row[1] }}</li>@endforeach
                </ul></div>
            </div>

            <aside>
                <div class="inquiry-card">
                    <div class="agent">
                        <div class="av"></div>
                        <div><div class="name">Credence RE Advisory</div><div class="role">Licensed Real Estate Advisors</div></div>
                    </div>
                    <h3>{{ $isLand ? 'Request documents' : 'Book a viewing' }}</h3>
                    <div class="ref-tag">REF · {{ $l->reference }} · {{ $locShort }}</div>
                    <div class="inquiry-actions">
                        <a href="{{ $waHref }}" target="_blank" rel="noopener" class="wa"><x-icon name="wa"/> WhatsApp</a>
                        <a href="tel:{{ config('cream.phone.tel') }}"><x-icon name="phone"/> Call</a>
                    </div>
                    <form action="https://api.web3forms.com/submit" method="POST" data-web3>
                        <input type="hidden" name="access_key" value="{{ config('cream.web3forms.listing_key') }}"/>
                        <input type="hidden" name="subject" value="Property Enquiry — {{ $l->title }} ({{ $l->reference }})"/>
                        <input type="hidden" name="from_name" value="Credence RE Website"/>
                        <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
                        <input type="text" name="name" placeholder="Full name" required/>
                        <input type="email" name="email" placeholder="Email address" required/>
                        <input type="tel" name="phone" placeholder="Phone / WhatsApp"/>
                        <textarea name="message" placeholder="{{ $isLand ? 'Which documents would you like? Preferred contact time…' : 'Your message or preferred viewing time…' }}"></textarea>
                        <button type="submit" class="submit">{{ $isLand ? 'Request documents' : 'Submit enquiry' }}</button>
                        <div class="form-msg" style="display:none"></div>
                    </form>
                    <p class="small-note">Or call us on <a href="tel:{{ config('cream.phone.tel') }}">{{ config('cream.phone.display') }}</a> · By appointment only</p>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
