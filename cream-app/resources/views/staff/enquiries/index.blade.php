@extends('layouts.dashboard')

@section('title', 'Enquiries')
@section('heading', 'Enquiries')
@section('sub', 'Messages sent through the website forms.')

@push('head')
<style>
.tabs{display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap}
.tabs a{padding:8px 16px;border-radius:999px;font-size:13.5px;border:1px solid var(--line);color:var(--ink-2)}
.tabs a.on{background:var(--emerald);color:#fff;border-color:var(--emerald)}
.tabs a .n{font-family:var(--mono);font-size:11px;opacity:.7;margin-left:4px}
.typefilter{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:18px}
.typefilter a{font-family:var(--mono);font-size:11px;letter-spacing:.04em;padding:5px 11px;border-radius:999px;border:1px solid var(--line);color:var(--ink-3)}
.typefilter a.on{border-color:var(--gold);color:var(--gold-2);background:#FBF6EC}
.bulkbar{display:flex;gap:10px;align-items:center;margin-bottom:14px}
.bulkbar select{padding:8px 12px;border:1px solid var(--line-2);border-radius:var(--r-s)}
.enq{border:1px solid var(--line);border-radius:var(--r-m);padding:14px 16px;margin-bottom:10px;background:var(--paper);display:flex;gap:14px;align-items:flex-start}
.enq .chk{padding-top:4px}
.enq .main{flex:1;min-width:0}
.enq .top{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:baseline}
.enq .who{font-family:var(--serif);font-size:17px}
.enq .who a{color:inherit}
.enq .ref{font-family:var(--mono);font-size:10.5px;color:var(--ink-3)}
.enq .meta{font-size:12.5px;color:var(--ink-3);margin:3px 0}
.enq .snippet{color:var(--ink-2);line-height:1.5;margin-top:4px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
.enq .when{font-family:var(--mono);font-size:10.5px;color:var(--ink-3)}
.chip{font-family:var(--mono);font-size:9.5px;letter-spacing:.08em;text-transform:uppercase;padding:3px 8px;border-radius:999px;border:1px solid var(--line-2);color:var(--ink-3)}
.chip.t{color:var(--emerald);border-color:var(--emerald)}
</style>
@endpush

@section('content')
    <div class="tabs">
        @foreach (['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'closed' => 'Closed', 'spam' => 'Spam'] as $k => $label)
            <a href="{{ route('staff.enquiries.index', array_filter(['tab' => $k, 'type' => $activeType])) }}" class="{{ $tab === $k ? 'on' : '' }}">{{ $label }}<span class="n">{{ $counts[$k] }}</span></a>
        @endforeach
    </div>

    <div class="typefilter">
        <a href="{{ route('staff.enquiries.index', ['tab' => $tab]) }}" class="{{ $activeType ? '' : 'on' }}">All types</a>
        @foreach ($types as $type)
            <a href="{{ route('staff.enquiries.index', ['tab' => $tab, 'type' => $type->value]) }}" class="{{ $activeType === $type->value ? 'on' : '' }}">{{ $type->label() }}</a>
        @endforeach
    </div>

    @if ($enquiries->isEmpty())
        <div class="panel"><div class="panel-body muted">No {{ $tab }} enquiries{{ $activeType ? ' of this type' : '' }}.</div></div>
    @else
        <form method="POST" action="{{ route('staff.enquiries.bulk') }}" id="bulkForm">
            @csrf
            <div class="bulkbar">
                <select name="action" required>
                    <option value="">Bulk action…</option>
                    <option value="read">Mark read</option>
                    <option value="replied">Mark replied</option>
                    <option value="closed">Mark closed</option>
                    <option value="spam">Mark spam</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="submit" class="btn btn-ghost" onclick="return confirm('Apply to selected enquiries?')">Apply to selected</button>
            </div>

            @foreach ($enquiries as $e)
                <div class="enq">
                    <div class="chk"><input type="checkbox" name="ids[]" value="{{ $e->id }}" form="bulkForm"></div>
                    <div class="main">
                        <div class="top">
                            <div class="who"><a href="{{ route('staff.enquiries.show', $e) }}">{{ $e->name }}</a></div>
                            <span class="when">{{ $e->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="meta">
                            <span class="chip t">{{ $e->type->label() }}</span>
                            <span class="ref">{{ $e->reference }}</span>
                            @if ($e->email) · {{ $e->email }}@endif
                            @if ($e->phone) · {{ $e->phone }}@endif
                            @if ($e->asset) · {{ $e->asset->reference }}@endif
                        </div>
                        @if ($e->message)<div class="snippet">{{ $e->message }}</div>@endif
                    </div>
                </div>
            @endforeach
        </form>

        <div style="margin-top:16px">{{ $enquiries->links() }}</div>
    @endif
@endsection
