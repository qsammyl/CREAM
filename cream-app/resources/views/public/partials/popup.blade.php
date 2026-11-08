@if ($popupAsset ?? null)
    @php
        $cover = $popupAsset->cover();
        $img = $cover?->url();
        $priceFrom = $popupAsset->isPriceOnRequest() ? 'Price on request' : ('From '.$popupAsset->formattedPrice());
        $lineParts = array_filter([
            $popupAsset->location,
            $popupAsset->beds ? $popupAsset->beds.' beds' : null,
            $popupAsset->expected_delivery ? 'delivery '.$popupAsset->expected_delivery : null,
        ]);
    @endphp
    <div class="cr-modal-scrim" id="crPopup" data-popup>
        <div class="cr-modal {{ $img ? '' : 'no-image' }}" role="dialog" aria-modal="true" aria-labelledby="crPopupTitle">
            <button class="cr-close" aria-label="Close"><x-icon name="x"/></button>
            @if ($img)
                <div class="cr-photo" style="background-image:url('{{ $img }}')"></div>
            @endif
            <div class="cr-body">
                <div class="cr-eyebrow">NEW PROJECT</div>
                <h2 id="crPopupTitle">{{ $popupAsset->title }}</h2>
                @if ($lineParts)<p class="cr-line">{{ implode(' · ', $lineParts) }}</p>@endif
                <p class="cr-line">{{ $priceFrom }}</p>
                <div class="cr-actions">
                    <a class="btn-solid" href="/listings/{{ $popupAsset->slug }}">View project <x-icon name="arrow"/></a>
                    <a class="btn-ghost" href="/new-projects#register">Register interest</a>
                </div>
                <p class="cr-foot">Get first access to off-plan prices and site updates.</p>
            </div>
        </div>
    </div>
@endif
