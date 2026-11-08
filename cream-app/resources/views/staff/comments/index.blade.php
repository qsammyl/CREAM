@extends('layouts.dashboard')

@section('title', 'Comments')
@section('heading', 'Comments')
@section('sub', 'Moderate visitor comments on articles.')

@push('head')
<style>
.tabs{display:flex;gap:6px;margin-bottom:18px;flex-wrap:wrap}
.tabs a{padding:8px 16px;border-radius:999px;font-size:13.5px;border:1px solid var(--line);color:var(--ink-2)}
.tabs a.on{background:var(--emerald);color:#fff;border-color:var(--emerald)}
.tabs a .n{font-family:var(--mono);font-size:11px;opacity:.7;margin-left:4px}
.cmt{border:1px solid var(--line);border-radius:var(--r-m);padding:16px 18px;margin-bottom:12px;background:var(--paper);display:flex;gap:14px}
.cmt .chk{padding-top:3px}
.cmt .main{flex:1;min-width:0}
.cmt .top{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
.cmt .who{font-family:var(--serif);font-size:17px}
.cmt .art{font-size:12.5px;color:var(--ink-3)}
.cmt .when{font-family:var(--mono);font-size:10.5px;color:var(--ink-3)}
.cmt .txt{color:var(--ink-2);line-height:1.6;margin:8px 0 12px;white-space:pre-wrap}
.cmt .acts{display:flex;gap:8px;flex-wrap:wrap}
.cmt .acts button{font-family:var(--mono);font-size:11px;padding:6px 12px;border-radius:var(--r-s);border:1px solid var(--line-2)}
.cmt .acts .ok{color:var(--ok-ink);border-color:var(--ok-bd)}
.cmt .acts .sp{color:var(--warn-ink);border-color:var(--warn-bd)}
.cmt .acts .del{color:var(--err-ink);border-color:var(--err-bd)}
.bulkbar{display:flex;gap:10px;align-items:center;margin-bottom:14px}
.bulkbar select{padding:8px 12px;border:1px solid var(--line-2);border-radius:var(--r-s)}
</style>
@endpush

@section('content')
    <div class="tabs">
        @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'spam' => 'Spam'] as $k => $label)
            <a href="{{ route('staff.comments.index', ['tab' => $k]) }}" class="{{ $tab === $k ? 'on' : '' }}">{{ $label }}<span class="n">{{ $counts[$k] }}</span></a>
        @endforeach
    </div>

    @if ($comments->isEmpty())
        <div class="panel"><div class="panel-body muted">No {{ $tab }} comments.</div></div>
    @else
        <form method="POST" action="{{ route('staff.comments.bulk') }}" id="bulkForm">
            @csrf
            <div class="bulkbar">
                <select name="action" required>
                    <option value="">Bulk action…</option>
                    <option value="approve">Approve</option>
                    <option value="spam">Mark as spam</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="submit" class="btn btn-ghost" onclick="return confirm('Apply to selected comments?')">Apply to selected</button>
            </div>

            @foreach ($comments as $c)
                <div class="cmt">
                    <div class="chk"><input type="checkbox" name="ids[]" value="{{ $c->id }}" form="bulkForm"></div>
                    <div class="main">
                        <div class="top">
                            <div><span class="who">{{ $c->name }}</span> <span class="art">on <a href="{{ $c->article?->url() }}" target="_blank">{{ $c->article?->title }}</a></span></div>
                            <span class="when">{{ $c->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="txt">{{ $c->body }}</div>
                        <div class="acts">
                            @if ($c->status->value !== 'approved')
                                <button type="submit" class="ok" form="approve-{{ $c->id }}">Approve</button>
                            @endif
                            @if ($c->status->value !== 'spam')
                                <button type="submit" class="sp" form="spam-{{ $c->id }}">Mark as spam</button>
                            @endif
                            <button type="submit" class="del" form="del-{{ $c->id }}" onclick="return confirm('Delete this comment?')">Delete</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </form>

        {{-- Per-row action forms (kept outside the bulk form) --}}
        @foreach ($comments as $c)
            <form id="approve-{{ $c->id }}" method="POST" action="{{ route('staff.comments.approve', $c) }}">@csrf</form>
            <form id="spam-{{ $c->id }}" method="POST" action="{{ route('staff.comments.spam', $c) }}">@csrf</form>
            <form id="del-{{ $c->id }}" method="POST" action="{{ route('staff.comments.destroy', $c) }}">@csrf @method('DELETE')</form>
        @endforeach

        <div style="margin-top:16px">{{ $comments->links() }}</div>
    @endif
@endsection
