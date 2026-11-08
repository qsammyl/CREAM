<p>{{ $comments->count() }} new comment(s) are awaiting moderation on the CREAM site:</p>

@foreach ($comments as $c)
    <hr>
    <p>
        <strong>{{ $c->name }}</strong> on &ldquo;{{ $c->article?->title }}&rdquo;
        <br>
        {{ \Illuminate\Support\Str::limit($c->body, 300) }}
    </p>
@endforeach

<hr>
<p><a href="{{ route('staff.comments.index') }}">Moderate in the dashboard &rarr;</a></p>
<p style="color:#888;font-size:12px">You are receiving this because you are the CREAM moderation contact. At most one digest per hour.</p>
