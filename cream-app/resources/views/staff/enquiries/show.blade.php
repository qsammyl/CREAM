@extends('layouts.dashboard')

@section('title', 'Enquiry · '.$enquiry->reference)
@section('heading', $enquiry->type->label())
@section('sub', $enquiry->reference.' · received '.$enquiry->created_at->format('d M Y H:i'))

@section('actions')
    <a class="btn btn-ghost" href="{{ route('staff.enquiries.index') }}">Back to inbox</a>
@endsection

@push('head')
<style>
.enq-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start}
.kv{width:100%;border-collapse:collapse}
.kv th{text-align:left;font-family:var(--mono);font-size:10.5px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-3);font-weight:400;padding:9px 14px 9px 0;vertical-align:top;white-space:nowrap}
.kv td{padding:9px 0;border-bottom:1px solid var(--line);color:var(--ink);font-size:14.5px}
.kv tr:last-child td{border-bottom:0}
.msgbox{margin-top:18px;background:var(--paper);border:1px solid var(--line);border-radius:var(--r-m);padding:18px;line-height:1.7;color:var(--ink-2);white-space:pre-wrap}
.side .card{border:1px solid var(--line);border-radius:var(--r-m);padding:18px;margin-bottom:16px}
.side h3{font-family:var(--mono);font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-3);margin:0 0 12px}
.reply-row{display:flex;flex-direction:column;gap:8px}
.reply-row a{display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;border-radius:var(--r-s);border:1px solid var(--line-2);font-size:13.5px;color:var(--ink)}
.reply-row a.wa{background:#25D366;border-color:#25D366;color:#073}
.status-form{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.status-form select{flex:1;padding:9px 11px;border:1px solid var(--line-2);border-radius:var(--r-s)}
.badge{display:inline-block;font-family:var(--mono);font-size:10.5px;letter-spacing:.08em;text-transform:uppercase;padding:4px 10px;border-radius:999px;border:1px solid var(--line-2);color:var(--ink-2)}
@media(max-width:820px){.enq-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    <div class="enq-grid">
        <div>
            <table class="kv">
                <tr><th>Status</th><td><span class="badge">{{ $enquiry->status->label() }}</span></td></tr>
                <tr><th>Name</th><td>{{ $enquiry->name }}</td></tr>
                @if ($enquiry->email)<tr><th>Email</th><td><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td></tr>@endif
                @if ($enquiry->phone)<tr><th>Phone</th><td>{{ $enquiry->phone }}</td></tr>@endif
                @if ($enquiry->country)<tr><th>Country</th><td>{{ $enquiry->country }}</td></tr>@endif
                @if ($enquiry->asset)
                    <tr><th>Property</th><td><a href="{{ route('staff.assets.edit', $enquiry->asset) }}">{{ $enquiry->asset->title }}</a> ({{ $enquiry->asset->reference }})</td></tr>
                @endif
                @foreach ($enquiry->type->detailLabels() as $key => $label)
                    @if (!empty($enquiry->details[$key]))
                        <tr><th>{{ $label }}</th><td>{{ $enquiry->details[$key] }}</td></tr>
                    @endif
                @endforeach
                @if ($enquiry->newsletter_opt_in)<tr><th>Newsletter</th><td>Opted in</td></tr>@endif
                @if ($enquiry->source_url)<tr><th>From page</th><td class="muted" style="word-break:break-all">{{ $enquiry->source_url }}</td></tr>@endif
            </table>

            @if ($enquiry->message)
                <div class="msgbox">{{ $enquiry->message }}</div>
            @endif
        </div>

        <aside class="side">
            <div class="card">
                <h3>Reply</h3>
                <div class="reply-row">
                    @if ($enquiry->email)
                        <a href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: '.($enquiry->subject ?: $enquiry->type->subject()).' ['.$enquiry->reference.']') }}">✉ Email {{ $enquiry->name }}</a>
                    @endif
                    @if ($enquiry->whatsappUrl())
                        <a class="wa" href="{{ $enquiry->whatsappUrl() }}" target="_blank" rel="noopener">WhatsApp</a>
                    @endif
                    @if (! $enquiry->email && ! $enquiry->whatsappUrl())
                        <p class="muted" style="font-size:13px">No contact channel was provided.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <h3>Update status</h3>
                <form method="POST" action="{{ route('staff.enquiries.status', $enquiry) }}" class="status-form">
                    @csrf @method('PATCH')
                    <select name="status">
                        @foreach (['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'closed' => 'Closed', 'spam' => 'Spam'] as $v => $label)
                            <option value="{{ $v }}" {{ $enquiry->status->value === $v ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-ghost">Save</button>
                </form>
                @if ($enquiry->handler)
                    <p class="muted" style="font-size:12px;margin-top:10px">Last handled by {{ $enquiry->handler->name }} · {{ $enquiry->handled_at?->diffForHumans() }}</p>
                @endif
            </div>

            <div class="card">
                <h3>Danger zone</h3>
                <form method="POST" action="{{ route('staff.enquiries.destroy', $enquiry) }}" data-confirm="Delete this enquiry?">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost" style="color:var(--err-ink);border-color:var(--err-bd)">Delete enquiry</button>
                </form>
            </div>
        </aside>
    </div>
@endsection
