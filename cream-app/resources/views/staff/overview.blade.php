@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('sub', 'Welcome back, ' . auth()->user()->name . '.')

@section('content')
    <div class="stat-row">
        <div class="stat">
            <div class="k">Staff accounts</div>
            <div class="v">{{ $stats['staff_total'] }}</div>
        </div>
        <div class="stat">
            <div class="k">Administrators</div>
            <div class="v">{{ $stats['admins'] }}</div>
        </div>
        <div class="stat">
            <div class="k">Active accounts</div>
            <div class="v">{{ $stats['active'] }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head"><h2>Foundation ready</h2></div>
        <div class="panel-body prose">
            <p>This is the Phase&nbsp;2 foundation of the CREAM staff dashboard: secure login,
               roles, two-factor authentication, the activity log and these base screens.</p>
            <p class="muted">Coming in the next phases: assets &amp; media, articles &amp; comments,
               testimonials &amp; QR codes, the enquiries inbox, settings &amp; users, and analytics.</p>
        </div>
    </div>

    @if (auth()->user()->isAdmin())
        <div class="panel">
            <div class="panel-head">
                <h2>Recent activity</h2>
                <a href="{{ route('staff.activity') }}" class="btn btn-ghost">View all</a>
            </div>
            @if ($recentActivity->isEmpty())
                <div class="panel-body muted">No activity recorded yet.</div>
            @else
                <table class="data">
                    <thead>
                        <tr><th>When</th><th>Who</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($recentActivity as $log)
                            <tr>
                                <td class="muted">{{ $log->created_at->diffForHumans() }}</td>
                                <td>{{ $log->user?->name ?? '—' }}</td>
                                <td><span class="tag">{{ $log->action }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
@endsection
