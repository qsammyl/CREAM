@extends('public.layout')

@section('content')
    <x-page-hero
        :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Short stays']]"
        h1="Short-stay<br/><em>homes.</em>"
        sub="Furnished homes for short stays in Accra. No booking engine — tell us your dates and we match you to the right home."/>

    <section class="sec bone2"><div class="container">
        @if ($items->isEmpty())
            <div class="cr-empty"><h3>Short-stay homes are coming soon.</h3><p>Tell us your dates and we will match you.</p></div>
        @else
            <div class="cr-grid">@foreach ($items as $a)<x-listing-card :asset="$a"/>@endforeach</div>
        @endif
    </div></section>

    <section class="sec bone" id="request"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Request dates</div><h2 class="t">Tell us your <em>dates.</em></h2></div><div class="right">Send your check-in and check-out dates and guest count — we will come back with available homes and nightly rates.</div></div>
        <form class="cr-form" style="max-width:720px" action="https://api.web3forms.com/submit" method="POST" data-web3>
            <input type="hidden" name="access_key" value="{{ config('cream.web3forms.contact_key') }}"/>
            <input type="hidden" name="subject" value="Short-stay — Request dates"/>
            <input type="hidden" name="from_name" value="Credence Website"/>
            <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
            <div class="row-2"><div><label>Check-in</label><input type="date" name="checkin"/></div><div><label>Check-out</label><input type="date" name="checkout"/></div></div>
            <div class="row-2"><div><label>Guests</label><input type="number" name="guests" min="1" placeholder="2"/></div><div><label>Full name</label><input name="name" required/></div></div>
            <div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div>
            <div><label>Anything else?</label><textarea name="message" placeholder="Preferred area, budget per night…"></textarea></div>
            <button type="submit" class="submit">Request dates <x-icon name="arrow"/></button>
            <div class="form-msg" style="display:none"></div>
        </form>
    </div></section>
@endsection
