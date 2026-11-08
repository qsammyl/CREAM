@extends('public.layout')

@push('head')
<style>
.steps-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;border-top:1px solid var(--line);margin-top:8px}
.dstep{padding:28px 22px 24px;border-bottom:1px solid var(--line)}.dstep:not(:last-child){border-right:1px solid var(--line)}
.dstep .n{font-family:var(--mono);font-size:11px;letter-spacing:.18em;color:var(--gold-2)}
.dstep .t{font-family:var(--serif);font-size:24px;margin:10px 0 6px;letter-spacing:-0.01em}
.dstep .d{font-size:14px;color:var(--ink-3);line-height:1.6}
.covers{display:grid;grid-template-columns:repeat(2,1fr);gap:12px 28px;margin-top:8px}
.covers .c{display:flex;gap:10px;font-size:15px;color:var(--ink-2);align-items:flex-start}
.covers .c .tick{width:18px;height:18px;border-radius:50%;background:var(--emerald);color:#fff;display:grid;place-items:center;flex:none;margin-top:2px}
.priceline{font-family:var(--serif);font-size:40px;letter-spacing:-0.01em}
@media(max-width:1080px){.steps-grid{grid-template-columns:1fr 1fr}.dstep:nth-child(2){border-right:0}}
@media(max-width:640px){.steps-grid{grid-template-columns:1fr}.dstep{border-right:0!important}.covers{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Search Document']]"
        h1="Official land<br/><em>document search.</em>"
        sub="An official land search at the Lands Commission confirms who really owns a plot, whether the title is clean, and whether anyone else has a claim on it. For any buyer in Ghana, it is the step to take before you pay for land."/>

    <section class="sec bone"><div class="container">
        <div class="eyebrow"><span class="dot"></span>How it works</div>
        <h2 class="t" style="margin:10px 0 0">Four steps, one <em>turnaround.</em></h2>
        <div class="steps-grid">
            <div class="dstep"><div class="n">01</div><div class="t">Submit</div><div class="d">Send us the plot location and your site plan or allocation papers through the form below.</div></div>
            <div class="dstep"><div class="n">02</div><div class="t">Pay</div><div class="d">We confirm the fee and send Mobile Money or bank transfer instructions by WhatsApp or email.</div></div>
            <div class="dstep"><div class="n">03</div><div class="t">We search</div><div class="d">Our team runs the official search at the Lands Commission and verifies survey and encumbrances.</div></div>
            <div class="dstep"><div class="n">04</div><div class="t">You get the report</div><div class="d">You receive a clear written report with our findings and recommendation.</div></div>
        </div>
        <p class="small" style="margin-top:16px">Typical turnaround: <strong>___ working days</strong>. {{-- TODO(sam): set the real turnaround time in working days. --}}</p>
    </div></section>

    <section class="sec bone2"><div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px">
            <div>
                <div class="eyebrow"><span class="dot"></span>What the search covers</div>
                <h2 class="t" style="margin:10px 0 18px">We check the <em>whole chain.</em></h2>
                <div class="covers">
                    <div class="c"><span class="tick"><x-icon name="check"/></span>Title search at the Lands Commission</div>
                    <div class="c"><span class="tick"><x-icon name="check"/></span>Survey &amp; boundary verification</div>
                    <div class="c"><span class="tick"><x-icon name="check"/></span>Encumbrances &amp; competing claims</div>
                    <div class="c"><span class="tick"><x-icon name="check"/></span>Chief / family verification where relevant</div>
                </div>
            </div>
            <div>
                <div class="eyebrow"><span class="dot"></span>Price &amp; what to provide</div>
                <h2 class="t" style="margin:10px 0 12px">From GHS ___</h2>
                {{-- TODO(sam): set the real "from" price in GHS. --}}
                <p class="prose" style="color:var(--ink-2);margin-bottom:12px">You will need to provide: the <strong>site plan</strong>, the <strong>indenture or allocation papers</strong>, and the <strong>plot location</strong>.</p>
                <p class="small"><strong>Payment:</strong> there is no online payment here. Once we review your request, we send Mobile Money or bank-transfer instructions by WhatsApp or email.</p>
            </div>
        </div>
    </div></section>

    <section class="sec bone" id="request"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Request a search</div><h2 class="t">Start your <em>land search.</em></h2></div><div class="right">Complete the form and we will confirm the fee and next steps. Your information is handled per our privacy notice.</div></div>
        @include('public.partials.form-status')
        <form class="cr-form" style="max-width:760px" action="{{ route('enquiries.store') }}" method="POST" data-enquiry>
            @csrf
            <input type="hidden" name="type" value="land_search"/>
            <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
            <div class="row-2"><div><label>Full name</label><input name="name" required/></div><div><label>Email</label><input type="email" name="email" required/></div></div>
            <div class="row-2"><div><label>Phone / WhatsApp</label><input type="tel" name="phone" required/></div><div><label>Country / region</label><input name="country"/></div></div>
            <div class="row-2"><div><label>Plot location</label><input name="plot_location" required/></div><div><label>Search type</label><select name="search_type"><option>Official search</option><option>Search with site visit</option></select></div></div>
            <div><label>Message</label><textarea name="message" placeholder="Anything we should know about the plot…"></textarea></div>
            @include('partials.turnstile')
            <button type="submit" class="submit">Request land search <x-icon name="arrow"/></button>
            <div class="form-msg" style="display:none"></div>
            <p class="privacy-note">We will ask you to send the site plan by WhatsApp or email after you submit. Your documents are handled per our <a href="/privacy">privacy notice</a>.</p>
        </form>
    </div></section>
@endsection
