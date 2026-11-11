@extends('layouts.dashboard')

@section('title', 'Testimonials')
@section('heading', 'Testimonials')
@section('sub', 'Moderate client feedback. Only feedback whose author agreed to publication can be published.')

@section('actions')
    <a class="btn btn-ghost" href="{{ route('staff.testimonials.qr') }}" target="_blank">Printable QR card</a>
@endsection

@push('head')
<style>
.tabs{display:flex;gap:6px;margin-bottom:18px;flex-wrap:wrap}
.tabs a{padding:8px 16px;border-radius:999px;font-size:13.5px;border:1px solid var(--line);color:var(--ink-2)}
.tabs a.on{background:var(--emerald);color:#fff;border-color:var(--emerald)}
.tabs a .n{font-family:var(--mono);font-size:11px;opacity:.7;margin-left:4px}
.tst{border:1px solid var(--line);border-radius:var(--r-m);padding:16px 18px;margin-bottom:12px;background:var(--paper);display:flex;gap:14px}
.tst .chk{padding-top:3px}
.tst .main{flex:1;min-width:0}
.tst .top{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:baseline}
.tst .who{font-family:var(--serif);font-size:17px}
.tst .role{font-size:12.5px;color:var(--ink-3)}
.tst .when{font-family:var(--mono);font-size:10.5px;color:var(--ink-3)}
.tst .stars{color:var(--gold);letter-spacing:1px;font-size:14px;margin:4px 0}
.tst .txt{color:var(--ink-2);line-height:1.6;margin:6px 0 10px;white-space:pre-wrap}
.tst .flags{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px}
.tag{font-family:var(--mono);font-size:10px;letter-spacing:.08em;text-transform:uppercase;padding:3px 8px;border-radius:999px;border:1px solid var(--line-2);color:var(--ink-3)}
.tag.consent{color:var(--ok-ink);border-color:var(--ok-bd);background:#F0FAF4}
.tag.noconsent{color:var(--warn-ink);border-color:var(--warn-bd);background:#FFF8EC}
.tag.pin{color:var(--emerald);border-color:var(--emerald)}
.tst .acts{display:flex;gap:8px;flex-wrap:wrap}
.tst .acts button{font-family:var(--mono);font-size:11px;padding:6px 12px;border-radius:var(--r-s);border:1px solid var(--line-2);cursor:pointer}
.tst .acts .ok{color:var(--ok-ink);border-color:var(--ok-bd)}
.tst .acts .ar{color:var(--ink-2)}
.tst .acts .sp{color:var(--warn-ink);border-color:var(--warn-bd)}
.tst .acts .del{color:var(--err-ink);border-color:var(--err-bd)}
.tst .acts .pin{color:var(--emerald);border-color:var(--emerald)}
.noconsent-note{font-size:12px;color:var(--warn-ink);margin:2px 0 10px}
.editbox{margin-top:10px;border-top:1px dashed var(--line);padding-top:10px}
.editbox summary{font-family:var(--mono);font-size:11px;color:var(--ink-3);cursor:pointer}
.editbox .grid{display:grid;grid-template-columns:2fr 2fr 1fr 1fr auto;gap:8px;margin-top:10px;align-items:end}
.editbox label{display:block;font-family:var(--mono);font-size:9.5px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-3);margin-bottom:3px}
.editbox input{width:100%;padding:7px 9px;border:1px solid var(--line-2);border-radius:var(--r-s);font:inherit;font-size:13px}
.bulkbar{display:flex;gap:10px;align-items:center;margin-bottom:14px}
.bulkbar select{padding:8px 12px;border:1px solid var(--line-2);border-radius:var(--r-s)}
@media(max-width:720px){.editbox .grid{grid-template-columns:1fr 1fr}}
</style>
@endpush

@section('content')
    <div class="tabs">
        @foreach (['pending' => 'Pending', 'approved' => 'Published', 'archived' => 'Archived', 'spam' => 'Spam'] as $k => $label)
            <a href="{{ route('staff.testimonials.index', ['tab' => $k]) }}" class="{{ $tab === $k ? 'on' : '' }}">{{ $label }}<span class="n">{{ $counts[$k] }}</span></a>
        @endforeach
    </div>

    @if ($testimonials->isEmpty())
        <div class="panel"><div class="panel-body muted">No {{ $tab }} testimonials.</div></div>
    @else
        <form method="POST" action="{{ route('staff.testimonials.bulk') }}" id="bulkForm">
            @csrf
            <div class="bulkbar">
                <select name="action" required>
                    <option value="">Bulk action…</option>
                    <option value="approve">Publish (consented only)</option>
                    <option value="archive">Archive</option>
                    <option value="spam">Mark as spam</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="submit" class="btn btn-ghost" onclick="return confirm('Apply to selected testimonials?')">Apply to selected</button>
            </div>

            @foreach ($testimonials as $t)
                <div class="tst">
                    <div class="chk"><input type="checkbox" name="ids[]" value="{{ $t->id }}" form="bulkForm"></div>
                    <div class="main">
                        <div class="top">
                            <div><span class="who">{{ $t->name }}</span> @if ($t->role)<span class="role">· {{ $t->role }}</span>@endif</div>
                            <span class="when">{{ $t->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="stars" aria-label="{{ $t->rating }} of 5">{{ str_repeat('★', $t->rating).str_repeat('☆', 5 - $t->rating) }}</div>
                        <div class="txt">{{ $t->body }}</div>
                        <div class="flags">
                            @if ($t->consent)
                                <span class="tag consent">✓ Consent to publish</span>
                            @else
                                <span class="tag noconsent">✕ No publish consent</span>
                            @endif
                            @if ($t->is_featured)<span class="tag pin">★ On homepage</span>@endif
                            @if ($t->source)<span class="tag">via {{ $t->source }}</span>@endif
                        </div>

                        @unless ($t->consent)
                            <div class="noconsent-note">Private feedback — the author did not agree to publication, so it can't be published. Archive it once read.</div>
                        @endunless

                        <div class="acts">
                            @if ($t->status->value !== 'approved' && $t->consent)
                                <button type="submit" class="ok" form="approve-{{ $t->id }}">Publish</button>
                            @endif
                            @if ($t->status->value === 'approved')
                                <button type="submit" class="pin" form="feature-{{ $t->id }}">{{ $t->is_featured ? 'Unpin from home' : 'Pin to home' }}</button>
                            @endif
                            @if ($t->status->value !== 'archived')
                                <button type="submit" class="ar" form="archive-{{ $t->id }}">Archive</button>
                            @endif
                            @if ($t->status->value !== 'spam')
                                <button type="submit" class="sp" form="spam-{{ $t->id }}">Spam</button>
                            @endif
                            <button type="submit" class="del" form="del-{{ $t->id }}" onclick="return confirm('Delete this testimonial?')">Delete</button>
                        </div>

                        <details class="editbox">
                            <summary>Edit display details</summary>
                            <form method="POST" action="{{ route('staff.testimonials.update', $t) }}">
                                @csrf @method('PUT')
                                <div class="grid">
                                    <div><label>Name</label><input name="name" value="{{ $t->name }}" required></div>
                                    <div><label>Role / area</label><input name="role" value="{{ $t->role }}" placeholder="Buyer · East Legon, 2025"></div>
                                    <div><label>Rating</label><input type="number" name="rating" min="1" max="5" value="{{ $t->rating }}" required></div>
                                    <div><label>Order</label><input type="number" name="sort_order" min="0" value="{{ $t->sort_order }}"></div>
                                    <div><button type="submit" class="btn btn-ghost" style="padding:7px 14px">Save</button></div>
                                </div>
                            </form>
                        </details>
                    </div>
                </div>
            @endforeach
        </form>

        {{-- Per-row action forms (kept outside the bulk form) --}}
        @foreach ($testimonials as $t)
            <form id="approve-{{ $t->id }}" method="POST" action="{{ route('staff.testimonials.approve', $t) }}">@csrf</form>
            <form id="feature-{{ $t->id }}" method="POST" action="{{ route('staff.testimonials.feature', $t) }}">@csrf</form>
            <form id="archive-{{ $t->id }}" method="POST" action="{{ route('staff.testimonials.archive', $t) }}">@csrf</form>
            <form id="spam-{{ $t->id }}" method="POST" action="{{ route('staff.testimonials.spam', $t) }}">@csrf</form>
            <form id="del-{{ $t->id }}" method="POST" action="{{ route('staff.testimonials.destroy', $t) }}">@csrf @method('DELETE')</form>
        @endforeach

        <div style="margin-top:16px">{{ $testimonials->links() }}</div>
    @endif
@endsection
