@extends('layouts.dashboard')

@section('title', 'Assets')
@section('heading', 'Assets')
@section('sub', $assets->total() . ' total')

@section('actions')
    <a href="{{ route('staff.assets.create') }}" class="btn btn-primary">+ New asset</a>
@endsection

@push('head')
<style>
.filters{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-bottom:20px}
.filters input[type=search],.filters select{padding:9px 12px;border:1px solid var(--line-2);border-radius:var(--r-s);background:#fff;font:inherit}
.filters input[type=search]{min-width:230px}
.filters label.chk{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--ink-2)}
.athumb{width:54px;height:40px;border-radius:6px;object-fit:cover;background:var(--bone-2);display:block}
.athumb-ph{width:54px;height:40px;border-radius:6px;background:var(--emerald-2);display:grid;place-items:center;color:#fff;font-family:var(--serif);font-size:13px}
.st{display:inline-block;font-family:var(--mono);font-size:10px;letter-spacing:.06em;text-transform:uppercase;padding:3px 9px;border-radius:999px}
.st.published{background:var(--ok-bg);color:var(--ok-ink)}
.st.draft{background:var(--bone-2);color:var(--ink-3)}
.st.under_offer{background:#F6ECD8;color:var(--gold-2)}
.st.sold,.st.let{background:var(--ink);color:#fff}
.st.archived{background:var(--err-bg);color:var(--err-ink)}
.rowacts{display:flex;gap:8px;flex-wrap:wrap}
.rowacts a,.rowacts button{font-family:var(--mono);font-size:11px;letter-spacing:.04em;color:var(--emerald-3)}
.rowacts button{color:var(--err-ink)}
.flagdot{font-family:var(--mono);font-size:10px;color:var(--gold-2)}
</style>
@endpush

@section('content')
    <form method="GET" class="filters">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search title, reference, area…"/>
        <select name="category" data-autosubmit>
            <option value="">All categories</option>
            @foreach ($categories as $v => $label)<option value="{{ $v }}" @selected(request('category') === $v)>{{ $label }}</option>@endforeach
        </select>
        <select name="status" data-autosubmit>
            <option value="">All statuses</option>
            @foreach ($statuses as $v => $label)<option value="{{ $v }}" @selected(request('status') === $v)>{{ $label }}</option>@endforeach
        </select>
        <select name="sort" data-autosubmit>
            <option value="updated" @selected(request('sort', 'updated') === 'updated')>Sort: Updated</option>
            <option value="price" @selected(request('sort') === 'price')>Sort: Price</option>
            <option value="title" @selected(request('sort') === 'title')>Sort: Title</option>
        </select>
        <label class="chk"><input type="checkbox" name="exclusive" value="1" data-autosubmit @checked(request('exclusive'))> Exclusive</label>
        <label class="chk"><input type="checkbox" name="featured" value="1" data-autosubmit @checked(request('featured'))> Featured</label>
        <button type="submit" class="btn btn-ghost">Apply</button>
        @if (request()->hasAny(['q', 'category', 'status', 'exclusive', 'featured', 'sort']))
            <a href="{{ route('staff.assets.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>

    <div class="panel">
        @if ($assets->isEmpty())
            <div class="panel-body muted">No assets match. <a href="{{ route('staff.assets.create') }}">Create one</a>.</div>
        @else
            <table class="data">
                <thead>
                    <tr><th></th><th>Title</th><th>Category</th><th>Status</th><th>Price</th><th>Updated</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach ($assets as $a)
                        @php($cover = $a->cover())
                        <tr>
                            <td>
                                @if ($cover)<img class="athumb" src="{{ $cover->url() }}" alt=""/>@else<span class="athumb-ph">C</span>@endif
                            </td>
                            <td>
                                <strong>{{ $a->title }}</strong>
                                <div class="muted" style="font-size:12px">{{ $a->reference }}@if ($a->location) · {{ $a->location_area ?: \Illuminate\Support\Str::of($a->location)->before('·') }}@endif</div>
                                <div class="flagdot">@if ($a->is_featured)★ featured @endif @if ($a->is_exclusive)◆ exclusive @endif</div>
                            </td>
                            <td>{{ $a->category->label() }}</td>
                            <td><span class="st {{ $a->status->value }}">{{ $a->status->label() }}</span></td>
                            <td>{{ $a->isPriceOnRequest() ? 'On request' : $a->formattedPrice() }}</td>
                            <td class="muted">{{ $a->updated_at->diffForHumans() }}</td>
                            <td>
                                <div class="rowacts">
                                    <a href="{{ route('staff.assets.edit', $a) }}">Edit</a>
                                    <a href="{{ route('staff.assets.preview', $a) }}" target="_blank">Preview</a>
                                    <form method="POST" action="{{ route('staff.assets.duplicate', $a) }}">@csrf<button type="submit" style="color:var(--emerald-3)">Duplicate</button></form>
                                    <form method="POST" action="{{ route('staff.assets.destroy', $a) }}" data-confirm="Move “{{ $a->title }}” to the bin?">@csrf @method('DELETE')<button type="submit">Bin</button></form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $assets->links() }}
@endsection
