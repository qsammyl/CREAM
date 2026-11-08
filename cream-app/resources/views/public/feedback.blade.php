@extends('public.layout')

@push('head')
<style>
.fb-wrap{max-width:620px;margin:0 auto}
.fb-card{background:var(--paper);border:1px solid var(--line);border-radius:var(--r-l);padding:clamp(22px,5vw,38px)}
.fb-card label{display:block;font-family:var(--mono);font-size:10.5px;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-3);margin:0 0 6px}
.fb-card input[type=text],.fb-card input[type=email],.fb-card textarea{width:100%;padding:13px 14px;border:1px solid var(--line-2);border-radius:var(--r-s);background:#fff;font:inherit}
.fb-card textarea{min-height:130px;resize:vertical}
.fb-field{margin-bottom:18px}
.fb-hint{font-family:inherit;text-transform:none;letter-spacing:0;font-size:12.5px;color:var(--ink-3);margin-top:6px}
.fb-err{color:#991B1B;font-size:12.5px;margin-top:5px}
/* CSS-only star rating (accessible radios, no inline JS) */
.rating{border:0;padding:0;margin:0 0 18px}
.rating-legend{font-family:var(--mono);font-size:10.5px;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-3);padding:0;margin:0 0 4px}
.stars-input{display:inline-flex;flex-direction:row-reverse;gap:6px;padding:4px 0}
.stars-input input{position:absolute;width:1px;height:1px;opacity:0;overflow:hidden}
.stars-input label{font-size:38px;line-height:1;color:#dadada;cursor:pointer;margin:0;text-transform:none;letter-spacing:0}
.stars-input label:hover,.stars-input label:hover ~ label,
.stars-input input:checked ~ label{color:var(--gold)}
.stars-input input:focus-visible + label{outline:2px solid var(--emerald);outline-offset:3px;border-radius:3px}
/* consent toggle */
.consent{display:flex;gap:11px;align-items:flex-start;background:#fff;border:1px solid var(--line-2);border-radius:var(--r-s);padding:14px 15px}
.consent-cb{margin-top:3px;width:18px;height:18px;flex:none}
.consent-lbl{text-transform:none;letter-spacing:0;font-family:inherit;font-size:14.5px;color:var(--ink);margin:0;line-height:1.5}
.cnote{font-size:13px;margin:9px 2px 0;line-height:1.5}
.cnote.on{display:none;color:var(--emerald-3)}
.cnote.off{color:var(--ink-3)}
.consent-cb:checked ~ .cnote.on{display:block}
.consent-cb:checked ~ .cnote.off{display:none}
.fb-hp{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden}
.fb-ok{background:#F0FAF4;border:1px solid #B6DFC7;color:#1A5C38;padding:16px 18px;border-radius:var(--r-m);margin-bottom:20px;line-height:1.6}
.fb-top-err{background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;padding:12px 15px;border-radius:var(--r-s);margin-bottom:16px}
.fb-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:560px){.fb-row{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Share your experience']]"
        h1="Share your<br/><em>experience.</em>"
        sub="A minute of your time helps us serve the next family better — and, with your permission, tells others what to expect."/>

    <section class="sec bone"><div class="container"><div class="fb-wrap">
        @if (session('feedback_status') === 'thanks')
            <div class="fb-ok">
                <strong>Thank you.</strong> Your feedback has reached our team. If you agreed to publication, it will appear on our
                <a href="/testimonials">client stories</a> page once reviewed.
            </div>
        @endif

        <div class="fb-card">
            @if ($errors->has('feedback'))<div class="fb-top-err">{{ $errors->first('feedback') }}</div>@endif

            <form method="POST" action="{{ route('feedback.store') }}">
                @csrf
                @if (request('src') === 'qr')<input type="hidden" name="src" value="qr">@endif

                <fieldset class="rating">
                    <legend class="rating-legend">Your rating</legend>
                    <div class="stars-input">
                        @foreach ([5, 4, 3, 2, 1] as $star)
                            <input type="radio" id="star{{ $star }}" name="rating" value="{{ $star }}"
                                {{ (int) old('rating', 5) === $star ? 'checked' : '' }}>
                            <label for="star{{ $star }}" title="{{ $star }} star{{ $star === 1 ? '' : 's' }}" aria-label="{{ $star }} star{{ $star === 1 ? '' : 's' }}">★</label>
                        @endforeach
                    </div>
                    @error('rating')<div class="fb-err">{{ $message }}</div>@enderror
                </fieldset>

                <div class="fb-field">
                    <label for="fb-body">Your experience</label>
                    <textarea id="fb-body" name="body" required placeholder="Tell us how we did — what went well, and anything we could do better.">{{ old('body') }}</textarea>
                    @error('body')<div class="fb-err">{{ $message }}</div>@enderror
                </div>

                <div class="fb-row">
                    <div class="fb-field">
                        <label for="fb-name">Your name</label>
                        <input type="text" id="fb-name" name="name" value="{{ old('name') }}" required>
                        @error('name')<div class="fb-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="fb-field">
                        <label for="fb-role">Role / area <span style="opacity:.6">(optional)</span></label>
                        <input type="text" id="fb-role" name="role" value="{{ old('role') }}" placeholder="e.g. Buyer · East Legon">
                        @error('role')<div class="fb-err">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="fb-field">
                    <label for="fb-email">Email <span style="opacity:.6">(optional, never published)</span></label>
                    <input type="email" id="fb-email" name="email" value="{{ old('email') }}">
                    <div class="fb-hint">Only so we can reach you if we need to — it is never shown publicly.</div>
                    @error('email')<div class="fb-err">{{ $message }}</div>@enderror
                </div>

                <div class="fb-field consent-box">
                    <div class="consent">
                        <input class="consent-cb" type="checkbox" id="consent" name="consent" value="1" {{ old('consent') ? 'checked' : '' }}>
                        <label class="consent-lbl" for="consent">I agree that Credence may publish my feedback as a testimonial on this website, shown with my name{{-- --}} and the role/area above.</label>
                    </div>
                    <p class="cnote off">Prefer to keep it private? Leave this unticked — your feedback still reaches our team and helps us improve; it just won't be published.</p>
                    <p class="cnote on">Thank you — with your permission we may feature your words (with your name) on our site. We'll only ever use what you wrote here.</p>
                </div>

                <div class="fb-hp" aria-hidden="true"><label>Website</label><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                @include('partials.turnstile')

                <button type="submit" class="btn-solid">Send feedback <x-icon name="arrow"/></button>
                <p class="fb-hint" style="margin-top:12px">See how we handle what you share in our <a href="/privacy">privacy notice</a>.</p>
            </form>
        </div>
    </div></div></section>
@endsection
