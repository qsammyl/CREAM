@extends('layouts.dashboard')

@section('title', 'Bin')
@section('heading', 'Bin')
@section('sub', 'Soft-deleted assets. Restoring and permanent deletion are admin-only.')

@section('content')
    <div class="panel">
        @if ($assets->isEmpty())
            <div class="panel-body muted">The bin is empty.</div>
        @else
            <table class="data">
                <thead><tr><th>Title</th><th>Reference</th><th>Deleted</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($assets as $a)
                        <tr>
                            <td><strong>{{ $a->title }}</strong></td>
                            <td class="muted">{{ $a->reference }}</td>
                            <td class="muted">{{ $a->deleted_at?->diffForHumans() }}</td>
                            <td>
                                @can('restore', $a)
                                    <div class="rowacts">
                                        <form method="POST" action="{{ route('staff.assets.restore', $a->id) }}">@csrf<button type="submit" style="color:var(--emerald-3)">Restore</button></form>
                                        <form method="POST" action="{{ route('staff.assets.force-delete', $a->id) }}" data-confirm="Permanently delete “{{ $a->title }}” and its photos? This cannot be undone.">@csrf @method('DELETE')<button type="submit" style="color:var(--err-ink)">Delete permanently</button></form>
                                    </div>
                                @else
                                    <span class="muted" style="font-size:12px">Admin only</span>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    {{ $assets->links() }}
@endsection
