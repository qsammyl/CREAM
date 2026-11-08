@extends('layouts.dashboard')

@section('title', 'Activity log')
@section('heading', 'Activity log')
@section('sub', 'Every create, update, delete, approval and login — admin only.')

@section('content')
    <div class="panel">
        @if ($logs->isEmpty())
            <div class="panel-body muted">No activity recorded yet.</div>
        @else
            <table class="data">
                <thead>
                    <tr><th>When</th><th>Who</th><th>Action</th><th>Subject</th><th>Details</th></tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td class="muted" title="{{ $log->created_at }}">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $log->user?->name ?? '—' }}</td>
                            <td><span class="tag {{ in_array($log->action, ['failed_login','lockout','delete']) ? 'err' : '' }}">{{ $log->action }}</span></td>
                            <td class="muted">
                                @if ($log->subject_type)
                                    {{ class_basename($log->subject_type) }}#{{ $log->subject_id }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="muted">{{ $log->properties ? json_encode($log->properties) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $logs->links() }}
@endsection
