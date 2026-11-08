@props(['chips' => [], 'countId' => '', 'area' => true, 'target' => '.cr-grid'])
<section class="filterbar" data-filterbar data-target="{{ $target }}" @if ($countId) data-count="#{{ $countId }}" @endif>
    <div class="inner">
        <span class="flabel">Filter</span>
        @foreach ($chips as $i => $c)
            <button class="chip{{ $i === 0 ? ' on' : '' }}" data-f="{{ $c['f'] }}" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}">{{ $c['label'] }}@isset($c['count'])<span style="font-family:var(--mono);font-size:10.5px;opacity:.6"> · {{ $c['count'] }}</span>@endisset</button>
        @endforeach
        @if ($area)
            <div class="areasel">
                <span class="flabel">Area</span>
                <select data-area-select aria-label="Filter by area">
                    <option value="all">All areas</option>
                    <option value="east-legon">East Legon</option>
                    <option value="ashale-botwe">Ashale Botwe</option>
                    <option value="accra">Accra &amp; other</option>
                </select>
            </div>
        @endif
    </div>
</section>
