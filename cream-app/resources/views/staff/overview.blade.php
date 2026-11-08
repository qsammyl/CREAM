@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('sub', 'Welcome back, ' . auth()->user()->name . '.')

@section('content')
    <div class="stat-row">
        <a class="stat" href="{{ route('staff.enquiries.index') }}" style="text-decoration:none;color:inherit">
            <div class="k">New enquiries</div>
            <div class="v">{{ $stats['new_enquiries'] }}</div>
        </a>
        <a class="stat" href="{{ route('staff.comments.index') }}" style="text-decoration:none;color:inherit">
            <div class="k">Comments to moderate</div>
            <div class="v">{{ $stats['pending_comments'] }}</div>
        </a>
        <a class="stat" href="{{ route('staff.testimonials.index') }}" style="text-decoration:none;color:inherit">
            <div class="k">Testimonials to review</div>
            <div class="v">{{ $stats['pending_testimonials'] }}</div>
        </a>
        <div class="stat">
            <div class="k">Active staff</div>
            <div class="v">{{ $stats['active'] }}</div>
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
