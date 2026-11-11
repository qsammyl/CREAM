@extends('layouts.dashboard')

@section('title', 'Articles')
@section('heading', 'Articles')
@section('sub', $articles->total() . ' total')

@section('actions')
    <a href="{{ route('staff.articles.create') }}" class="btn btn-primary">+ New article</a>
@endsection

@push('head')
<style>
.filters{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-bottom:20px}
.filters input[type=search],.filters select{padding:9px 12px;border:1px solid var(--line-2);border-radius:var(--r-s);background:#fff;font:inherit}
.st{display:inline-block;font-family:var(--mono);font-size:10px;letter-spacing:.06em;text-transform:uppercase;padding:3px 9px;border-radius:999px}
.st.published{background:var(--ok-bg);color:var(--ok-ink)}
.st.draft{background:var(--bone-2);color:var(--ink-3)}
.rowacts{display:flex;gap:10px}.rowacts a,.rowacts button{font-family:var(--mono);font-size:11px;color:var(--emerald-3)}.rowacts button{color:var(--err-ink)}
</style>
@endpush

@section('content')
    <form method="GET" class="filters">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search title…">
        <select name="status" data-autosubmit>
            <option value="">All statuses</option>
            @foreach ($statuses as $v => $l)<option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>@endforeach
        </select>
        <button type="submit" class="btn btn-ghost">Apply</button>
    </form>

    <div class="panel">
        @if ($articles->isEmpty())
            <div class="panel-body muted">No articles yet. <a href="{{ route('staff.articles.create') }}">Write one</a>.</div>
        @else
            <table class="data">
                <thead><tr><th>Title</th><th>Status</th><th>Author</th><th>Comments</th><th>Updated</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($articles as $a)
                        <tr>
                            <td><strong>{{ $a->title }}</strong><div class="muted" style="font-size:12px">{{ $a->legacy_path ?: '/blog/'.$a->slug }}</div></td>
                            <td><span class="st {{ $a->status->value }}">{{ $a->status->label() }}</span></td>
                            <td>{{ $a->authorLabel() }}</td>
                            <td class="muted">{{ $a->comments()->count() }}</td>
                            <td class="muted">{{ $a->updated_at->diffForHumans() }}</td>
                            <td>
                                <div class="rowacts rowacts-ic">
                                    <a href="{{ route('staff.articles.edit', $a) }}" class="act" aria-label="Edit article" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg></a>
                                    @if ($a->status->value === 'published')<a href="{{ $a->url() }}" target="_blank" rel="noopener" class="act" aria-label="View live post" title="View live"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></a>@endif
                                    <form method="POST" action="{{ route('staff.articles.destroy', $a) }}" data-confirm="Delete “{{ $a->title }}”?">@csrf @method('DELETE')<button type="submit" class="act act-del" aria-label="Delete article" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg></button></form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    {{ $articles->links() }}
@endsection
