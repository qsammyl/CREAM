@extends('public.layout')

@section('content')
    <x-page-hero
        :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Exclusives']]"
        h1="Exclusive<br/><em>listings.</em>"
        sub="A small number of homes we represent exclusively — plus access to the private, off-market side of our book."/>

    <section class="sec bone2">
        <div class="container">
            <div style="margin-bottom:28px" class="eyebrow"><span class="dot"></span>{{ $items->count() }} exclusive listing{{ $items->count() === 1 ? '' : 's' }}</div>
            @if ($items->isEmpty())
                <div class="cr-empty"><h3>No exclusive listings right now</h3><p>Join the off-market brief below to hear first.</p></div>
            @else
                <div class="cr-grid">
                    @foreach ($items as $a)<x-listing-card :asset="$a"/>@endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="sec ink" id="off-market">
        <div class="container">
            <div class="section-head"><div class="left"><div class="eyebrow" style="color:rgba(255,255,255,.65)"><span class="dot"></span>Off-market</div><h2 class="t" style="color:#fff">Submit a <em>brief</em>.</h2></div><div class="right" style="color:rgba(255,255,255,.72)">Tell us the brief — bedrooms, budget, location, timeline — and we will surface matching opportunities that never appear on the public site.</div></div>
            <form class="cr-form" style="max-width:720px;margin-top:8px" action="https://api.web3forms.com/submit" method="POST" data-web3>
                <input type="hidden" name="access_key" value="{{ config('cream.web3forms.contact_key') }}"/>
                <input type="hidden" name="subject" value="Off-market brief — Credence Real Estate"/>
                <input type="hidden" name="from_name" value="Credence Website"/>
                <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
                <div class="row-2"><div><label>Full name</label><input name="name" required/></div><div><label>Email</label><input type="email" name="email" required/></div></div>
                <div class="row-2"><div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div><div><label>Location / area</label><input name="location"/></div></div>
                <div class="row-2"><div><label>Budget (USD)</label><input name="budget" placeholder="e.g. 300K–500K"/></div><div><label>Bedrooms</label><input name="bedrooms"/></div></div>
                <div><label>Your brief</label><textarea name="message" placeholder="Intent (own or invest), timeline, must-haves…"></textarea></div>
                <button type="submit" class="submit">Submit brief <x-icon name="arrow"/></button>
                <div class="form-msg" style="display:none"></div>
            </form>
        </div>
    </section>
@endsection
