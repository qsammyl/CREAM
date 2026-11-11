@extends('layouts.dashboard')

@section('title', 'Staff users')
@section('heading', 'Staff users')
@section('sub', 'Manage who can sign in, and invite new team members.')

@push('head')
<style>
.u-row{display:flex;align-items:center;gap:14px}
.u-av{width:38px;height:38px;border-radius:50%;background:var(--emerald);color:#fff;display:grid;place-items:center;font-family:var(--serif);font-size:17px;flex:none}
.inactive{opacity:.55}
.tag.admin{color:var(--emerald);border-color:var(--emerald)}
.tag.off{color:var(--err-ink);border-color:var(--err-bd)}
.invite-form{display:grid;grid-template-columns:2fr 1fr auto;gap:10px;align-items:end}
.invite-form label{font-family:var(--mono);font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-3);display:block;margin-bottom:5px}
.invite-form input,.invite-form select{width:100%;padding:9px 11px;border:1px solid var(--line-2);border-radius:var(--r-s);font:inherit}
@media(max-width:640px){.invite-form{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-head"><h2>Invite a team member</h2></div>
        <div class="panel-body">
            <form method="POST" action="{{ route('staff.invitations.store') }}" class="invite-form">
                @csrf
                <div>
                    <label>Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                    @error('email')<div style="color:var(--err-ink);font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label>Role</label>
                    <select name="role">
                        <option value="staff">Staff</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div><button type="submit" class="btn btn-primary">Send invite</button></div>
            </form>
            <p class="muted" style="font-size:12.5px;margin-top:12px">The invitee sets their own password. Admins must enable two-factor authentication on first sign-in.</p>
        </div>
    </div>

    @if ($invitations->isNotEmpty())
        <div class="panel">
            <div class="panel-head"><h2>Pending invitations</h2></div>
            <table class="data">
                <thead><tr><th>Email</th><th>Role</th><th>Invited</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @foreach ($invitations as $inv)
                        <tr>
                            <td>{{ $inv->email }}</td>
                            <td>{{ $inv->role->label() }}</td>
                            <td class="muted">{{ $inv->created_at->diffForHumans() }}@if ($inv->inviter) · by {{ $inv->inviter->name }}@endif</td>
                            <td>@if ($inv->isExpired())<span class="tag off">Expired</span>@else<span class="tag">Awaiting</span>@endif</td>
                            <td style="text-align:right;white-space:nowrap">
                                <form method="POST" action="{{ route('staff.invitations.resend', $inv) }}" style="display:inline">@csrf<button class="btn btn-ghost" style="padding:5px 10px">Resend</button></form>
                                <form method="POST" action="{{ route('staff.invitations.destroy', $inv) }}" style="display:inline" data-confirm="Revoke this invitation?">@csrf @method('DELETE')<button class="btn btn-ghost" style="padding:5px 10px;color:var(--err-ink);border-color:var(--err-bd)">Revoke</button></form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head"><h2>Accounts <span class="muted" style="font-weight:400">· {{ $users->count() }}</span></h2></div>
        <table class="data">
            <thead><tr><th>Name</th><th>Role</th><th>Status</th><th>Last sign-in</th><th></th></tr></thead>
            <tbody>
                @foreach ($users as $u)
                    <tr class="{{ $u->is_active ? '' : 'inactive' }}">
                        <td>
                            <div class="u-row">
                                <div class="u-av">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                                <div><strong>{{ $u->name }}</strong><br><span class="muted" style="font-size:12.5px">{{ $u->email }}</span></div>
                            </div>
                        </td>
                        <td>@if ($u->isAdmin())<span class="tag admin">Administrator</span>@else<span class="tag">Staff</span>@endif</td>
                        <td>@if ($u->is_active)<span class="tag">Active</span>@else<span class="tag off">Inactive</span>@endif</td>
                        <td class="muted">{{ $u->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                        <td style="text-align:right"><a href="{{ route('staff.users.edit', $u) }}" class="btn btn-ghost" style="padding:5px 12px">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
