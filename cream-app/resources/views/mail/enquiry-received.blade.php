@php($e = $enquiry)
<p>A new <strong>{{ $e->type->label() }}</strong> has come in through the website.</p>

<table cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.6">
    <tr><td style="color:#888;padding-right:12px">Reference</td><td><strong>{{ $e->reference }}</strong></td></tr>
    <tr><td style="color:#888;padding-right:12px">Received</td><td>{{ $e->created_at->format('d M Y H:i') }}</td></tr>
    <tr><td style="color:#888;padding-right:12px">Name</td><td>{{ $e->name }}</td></tr>
    @if ($e->email)<tr><td style="color:#888;padding-right:12px">Email</td><td>{{ $e->email }}</td></tr>@endif
    @if ($e->phone)<tr><td style="color:#888;padding-right:12px">Phone / WhatsApp</td><td>{{ $e->phone }}</td></tr>@endif
    @if ($e->country)<tr><td style="color:#888;padding-right:12px">Country / region</td><td>{{ $e->country }}</td></tr>@endif
    @if ($e->asset)<tr><td style="color:#888;padding-right:12px">Property</td><td>{{ $e->asset->title }} ({{ $e->asset->reference }})</td></tr>@endif
    @foreach ($e->type->detailLabels() as $key => $label)
        @if (!empty($e->details[$key]))<tr><td style="color:#888;padding-right:12px">{{ $label }}</td><td>{{ $e->details[$key] }}</td></tr>@endif
    @endforeach
    @if ($e->newsletter_opt_in)<tr><td style="color:#888;padding-right:12px">Newsletter</td><td>Opted in</td></tr>@endif
</table>

@if ($e->message)
    <p style="margin-top:16px"><strong>Message</strong><br>{!! nl2br(e($e->message)) !!}</p>
@endif

<hr>
<p><a href="{{ $url }}">Open in the dashboard &rarr;</a></p>
<p style="color:#888;font-size:12px">
    @if ($e->email)Reply to this email to respond directly to {{ $e->name }}.
    @else No email was provided — reach {{ $e->name }} on the phone number above.@endif
</p>
