@props(['asset'])
@php
    if ($asset->isPriceOnRequest()) {
        $html = 'Price on request';
    } else {
        $html = e($asset->currency->value).' '.number_format((int) $asset->price);
        if ($asset->price_period === 'month') {
            $html .= ' <span class="per">/mo</span>';
        } elseif ($asset->price_period === 'night') {
            $html .= ' <span class="per">/night</span>';
        }
    }
@endphp
{!! $html !!}
