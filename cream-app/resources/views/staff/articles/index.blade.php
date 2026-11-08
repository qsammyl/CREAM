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
        <select name="status" onchange="this.form.submit()">
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
                                <div class="rowacts">
                                    <a href="{{ route('staff.articles.edit', $a) }}">Edit</a>
                                    @if ($a->status->value === 'published')<a href="{{ $a->url() }}" target="_blank">View</a>@endif
                                    <form method="POST" action="{{ route('staff.articles.destroy', $a) }}" onsubmit="return confirm('Delete “{{ $a->title }}”?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
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
