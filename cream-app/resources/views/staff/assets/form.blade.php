@extends('layouts.dashboard')

@php
    $editing = $asset->exists;
    $val = fn ($f, $d = null) => old($f, data_get($asset, $f) ?? $d);
    $enumVal = fn ($f) => old($f, data_get($asset, $f)?->value);
    $featuresText = old('features_text', $asset->features ? implode("\n", $asset->features) : '');
    $documentsText = old('documents_text', $asset->documents_available ? implode("\n", $asset->documents_available) : '');
@endphp

@section('title', $editing ? 'Edit asset' : 'New asset')
@section('heading', $editing ? 'Edit asset' : 'New asset')
@section('sub', $editing ? $asset->reference : 'Create a property, land plot, project or short stay')

@section('actions')
    @if ($editing)
        <a href="{{ route('staff.assets.preview', $asset) }}" target="_blank" class="btn btn-ghost">Preview</a>
    @endif
@endsection

@push('head')
<style>
.fgrid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.fgrid .full{grid-column:1 / -1}
.chkrow{display:flex;flex-wrap:wrap;gap:18px}
.chkrow label{display:inline-flex;align-items:center;gap:8px;font-size:14px;color:var(--ink-2)}
textarea.input{min-height:90px;resize:vertical;font-family:inherit}
.savebar{position:sticky;bottom:0;background:var(--paper);border-top:1px solid var(--line);padding:16px 0;margin-top:8px;display:flex;gap:10px;flex-wrap:wrap}
.media-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px}
.mcard{border:1px solid var(--line);border-radius:var(--r-m);overflow:hidden;background:var(--paper)}
.mcard img{width:100%;height:140px;object-fit:cover;display:block;background:var(--bone-2)}
.mcard .mb{padding:10px 12px;display:flex;flex-direction:column;gap:8px}
.mcard .cover-tag{font-family:var(--mono);font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--ok-ink)}
.mcard .macts{display:flex;gap:6px;flex-wrap:wrap;align-items:center}
.mcard .macts button,.mcard .macts a{font-family:var(--mono);font-size:10.5px;color:var(--emerald-3)}
.mcard .macts .del{color:var(--err-ink)}
@media(max-width:720px){.fgrid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-err">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $editing ? route('staff.assets.update', $asset) : route('staff.assets.store') }}">
        @csrf
        @if ($editing)@method('PUT')@endif

        {{-- BASICS --}}
        <div class="section-card">
            <h2>Basics</h2>
            <div class="fgrid">
                <div class="field full"><label>Title *</label><input name="title" class="input" value="{{ $val('title') }}" required></div>
                <div class="field"><label>Slug</label><input name="slug" class="input" value="{{ $val('slug') }}" placeholder="auto from title"><div class="hint">Changing this 301-redirects the old URL.</div></div>
                <div class="field"><label>Reference</label><input name="reference" class="input" value="{{ $val('reference') }}" placeholder="auto e.g. CR-XX-01"></div>
                <div class="field"><label>Category *</label><select name="category" class="input">@foreach ($categories as $v => $l)<option value="{{ $v }}" @selected($enumVal('category') === $v)>{{ $l }}</option>@endforeach</select></div>
                <div class="field"><label>Status *</label><select name="status" class="input">@foreach ($statuses as $v => $l)<option value="{{ $v }}" @selected($enumVal('status') === $v)>{{ $l }}</option>@endforeach</select></div>
                <div class="field full"><label>Summary</label><textarea name="summary" class="input">{{ $val('summary') }}</textarea></div>
                <div class="field full"><label>Second paragraph</label><textarea name="summary2" class="input">{{ $val('summary2') }}</textarea></div>
                <div class="field full"><label>Description (optional)</label><textarea name="description" class="input">{{ $val('description') }}</textarea><div class="hint">Plain text for now; a rich-text editor arrives in Phase 4.</div></div>
            </div>
        </div>

        {{-- PRICE --}}
        <div class="section-card">
            <h2>Price</h2>
            <div class="fgrid">
                <div class="field"><label>Price</label><input name="price" type="number" min="0" class="input" value="{{ $val('price') }}"></div>
                <div class="field"><label>Currency</label><select name="currency" class="input">@foreach ($currencies as $v => $l)<option value="{{ $v }}" @selected($enumVal('currency') === $v)>{{ $l }}</option>@endforeach</select></div>
                <div class="field"><label>Period</label><select name="price_period" class="input">@foreach (['total' => 'Total', 'month' => 'Per month', 'night' => 'Per night'] as $v => $l)<option value="{{ $v }}" @selected($val('price_period') === $v)>{{ $l }}</option>@endforeach</select></div>
                <div class="field"><label>Price note</label><input name="price_note" class="input" value="{{ $val('price_note') }}" placeholder="Asking Price · Negotiable"></div>
                <div class="field full"><label class="check"><input type="hidden" name="price_on_request" value="0"><input type="checkbox" name="price_on_request" value="1" @checked($val('price_on_request'))> Price on request (hides the figure)</label></div>
            </div>
        </div>

        {{-- DETAILS --}}
        <div class="section-card">
            <h2>Details</h2>
            <div class="fgrid">
                <div class="field"><label>Bedrooms</label><input name="beds" class="input" value="{{ $val('beds') }}"></div>
                <div class="field"><label>Bathrooms</label><input name="baths" class="input" value="{{ $val('baths') }}"></div>
                <div class="field"><label>Floor area</label><input name="floor_area" class="input" value="{{ $val('floor_area') }}"></div>
                <div class="field"><label>Plot size</label><input name="plot_size" class="input" value="{{ $val('plot_size') }}" placeholder="2.5 plots · 0.65 acres"></div>
                <div class="field"><label>Tenure</label><input name="tenure" class="input" value="{{ $val('tenure') }}"></div>
                <div class="field"><label>Survey</label><input name="survey" class="input" value="{{ $val('survey') }}"></div>
                <div class="field"><label>Access</label><input name="access" class="input" value="{{ $val('access') }}"></div>
                <div class="field"><label>Stage (new projects)</label><input name="stage" class="input" value="{{ $val('stage') }}"></div>
                <div class="field"><label>Expected delivery</label><input name="expected_delivery" class="input" value="{{ $val('expected_delivery') }}"></div>
                <div class="field"><label>Availability note</label><input name="availability" class="input" value="{{ $val('availability') }}"></div>
                <div class="field"><label>Max guests (short stay)</label><input name="max_guests" type="number" min="1" class="input" value="{{ $val('max_guests') }}"></div>
                <div class="field"><label>Video URL / path</label><input name="video_url" class="input" value="{{ $val('video_url') }}" placeholder="assets/walkthrough.mp4"></div>
                <div class="field full"><label>Key features (one per line)</label><textarea name="features_text" class="input">{{ $featuresText }}</textarea></div>
                <div class="field full"><label>Documents available (one per line)</label><textarea name="documents_text" class="input">{{ $documentsText }}</textarea></div>
            </div>
        </div>

        {{-- LOCATION --}}
        <div class="section-card">
            <h2>Location</h2>
            <div class="fgrid">
                <div class="field full"><label>Location line</label><input name="location" class="input" value="{{ $val('location') }}" placeholder="Ashale Botwe · School Junction, Accra"></div>
                <div class="field"><label>Area</label><input name="location_area" class="input" value="{{ $val('location_area') }}" placeholder="East Legon"></div>
                <div class="field"><label>Area key (filter)</label><input name="area_key" class="input" value="{{ $val('area_key') }}" placeholder="east-legon"></div>
                <div class="field"><label>City</label><input name="location_city" class="input" value="{{ $val('location_city', 'Accra') }}"></div>
                <div class="field"><label>Country</label><input name="country" class="input" value="{{ $val('country', 'Ghana') }}"></div>
                <div class="field"><label>Latitude</label><input name="latitude" class="input" value="{{ $val('latitude') }}"></div>
                <div class="field"><label>Longitude</label><input name="longitude" class="input" value="{{ $val('longitude') }}"></div>
            </div>
        </div>

        {{-- VERIFICATION --}}
        <div class="section-card">
            <h2>Verification</h2>
            <div class="fgrid">
                <div class="field full"><label class="check"><input type="hidden" name="title_verified" value="0"><input type="checkbox" name="title_verified" value="1" @checked($val('title_verified'))> Title verified</label></div>
                <div class="field full"><label>What was checked</label><textarea name="verified_summary" class="input">{{ $val('verified_summary') }}</textarea></div>
            </div>
        </div>

        {{-- VISIBILITY --}}
        <div class="section-card">
            <h2>Visibility</h2>
            <div class="chkrow" style="margin-bottom:14px">
                <label><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked($val('is_featured'))> Featured on homepage</label>
                <label><input type="hidden" name="is_exclusive" value="0"><input type="checkbox" name="is_exclusive" value="1" @checked($val('is_exclusive'))> Exclusive</label>
                <label><input type="hidden" name="is_off_plan" value="0"><input type="checkbox" name="is_off_plan" value="1" @checked($val('is_off_plan'))> Off-plan</label>
                <label><input type="hidden" name="is_furnished" value="0"><input type="checkbox" name="is_furnished" value="1" @checked($val('is_furnished'))> Furnished</label>
            </div>
            <div class="field" style="max-width:180px"><label>Sort order</label><input name="sort_order" type="number" class="input" value="{{ $val('sort_order', 0) }}"></div>
        </div>

        <div class="savebar">
            <button type="submit" name="publish" value="0" class="btn btn-ghost">Save draft</button>
            <button type="submit" name="publish" value="1" class="btn btn-primary">Save &amp; publish</button>
        </div>
    </form>

    {{-- MEDIA (edit only) --}}
    @if ($editing)
        <div class="section-card">
            <h2>Media</h2>
            <div class="desc">Photos are re-encoded to WebP (1600 &amp; 800px) and EXIF stripped on upload. Max 10 MB each.</div>

            <form method="POST" action="{{ route('staff.assets.media.store', $asset) }}" enctype="multipart/form-data" style="margin-bottom:18px">
                @csrf
                <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" required>
                <button type="submit" class="btn btn-gold" style="margin-left:8px">Upload photos</button>
            </form>

            @if ($asset->media->isEmpty())
                <p class="muted">No photos yet.</p>
            @else
                <div class="media-list">
                    @foreach ($asset->media as $m)
                        <div class="mcard">
                            <img src="{{ $m->url() }}" alt="{{ $m->alt }}">
                            <div class="mb">
                                @if ($m->is_cover)<span class="cover-tag">★ Cover photo</span>@endif
                                <form method="POST" action="{{ route('staff.assets.media.alt', $m) }}" style="display:flex;gap:6px">
                                    @csrf @method('PATCH')
                                    <input name="alt" class="input" style="padding:6px 8px;font-size:12px" value="{{ $m->alt }}" placeholder="Alt text">
                                    <button type="submit" class="btn btn-ghost" style="padding:6px 10px">Save</button>
                                </form>
                                <div class="macts">
                                    @unless ($m->is_cover)<form method="POST" action="{{ route('staff.assets.media.cover', $m) }}">@csrf<button type="submit">Set cover</button></form>@endunless
                                    <form method="POST" action="{{ route('staff.assets.media.move', [$m, 'up']) }}">@csrf<button type="submit">↑</button></form>
                                    <form method="POST" action="{{ route('staff.assets.media.move', [$m, 'down']) }}">@csrf<button type="submit">↓</button></form>
                                    <form method="POST" action="{{ route('staff.assets.media.destroy', $m) }}" data-confirm="Delete this photo?">@csrf @method('DELETE')<button type="submit" class="del">Delete</button></form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
@endsection
