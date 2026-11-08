@extends('public.layout')

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Sell with us']]"
        h1="Sell or let<br/><em>with Credence.</em>"
        sub="We represent owners selling or letting property in Accra — with honest valuations, verified documentation, and buyers from our local and diaspora network."/>

    <section class="sec bone2"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>How it works</div><h2 class="t">A clear, documented <em>process.</em></h2></div><div class="right">We verify your title, agree a realistic price, market the property to qualified buyers and tenants, and manage viewings and negotiation through to signing.</div></div>
        @include('public.partials.form-status')
        <form class="cr-form" style="max-width:760px" action="{{ route('enquiries.store') }}" method="POST" data-enquiry>
            @csrf
            <input type="hidden" name="type" value="sell"/>
            <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
            <div class="row-2"><div><label>Full name</label><input name="name" required/></div><div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div></div>
            <div class="row-2"><div><label>Email</label><input type="email" name="email" required/></div><div><label>Country / region</label><input name="country"/></div></div>
            <div class="row-2"><div><label>Property location</label><input name="property_location"/></div><div><label>Property type</label><select name="property_type"><option>House</option><option>Apartment</option><option>Land</option><option>Commercial</option><option>Other</option></select></div></div>
            <div class="row-2"><div><label>Bedrooms</label><input name="bedrooms"/></div><div><label>Asking price (optional)</label><input name="asking_price"/></div></div>
            <div class="row-2"><div><label>Sell or let?</label><select name="intent"><option>Sell</option><option>Let (rent out)</option><option>Not sure</option></select></div><div><label>Photos link (optional)</label><input name="photos_link" placeholder="Google Drive / Dropbox link"/></div></div>
            <div><label>Message</label><textarea name="message" placeholder="Tell us about the property…"></textarea></div>
            @include('partials.turnstile')
            <button type="submit" class="submit">Submit property <x-icon name="arrow"/></button>
            <div class="form-msg" style="display:none"></div>
            <p class="privacy-note">By submitting you agree to our <a href="/privacy">privacy notice</a>. We will contact you to arrange next steps.</p>
        </form>
    </div></section>
@endsection
