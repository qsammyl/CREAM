@extends('public.layout')

@php
    $topics = ['Buy a Property', 'Sell a Property', 'Build with Credence', 'Property Management', 'Interior Decor', 'Land & Due Diligence', 'Diaspora Programme', 'Press / Other'];
@endphp

@push('head')
<style>
.quick{background:var(--bone);padding:40px 0;border-bottom:1px solid var(--line)}
.quick-row{display:grid;grid-template-columns:1fr 1.5fr 1fr 1fr;gap:16px}
.qitem{display:flex;gap:14px;padding:18px 20px;border-radius:var(--r-m);background:var(--paper);border:1px solid var(--line);transition:all .25s ease}
.qitem:hover{border-color:var(--gold);transform:translateY(-2px);box-shadow:var(--shadow-1)}
.qitem .ic{width:42px;height:42px;border-radius:50%;background:var(--bone-2);display:grid;place-items:center;color:var(--emerald);flex:none}
.qitem .k{font-family:var(--mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3)}
.qitem .v{font-family:var(--serif);font-size:18px;line-height:1.15;margin-top:4px}
.qitem .d{font-size:12px;color:var(--ink-3);margin-top:3px}
.form-grid{display:grid;grid-template-columns:1.4fr 1fr;gap:56px;align-items:start}
.form-block h2{font-family:var(--serif);font-weight:400;font-size:clamp(32px,4vw,52px);line-height:1.04;margin:0 0 22px}
.form-block h2 em{font-style:italic;color:var(--gold)}
.side{display:flex;flex-direction:column;gap:20px;position:sticky;top:calc(var(--header-h) + 12px)}
.side .card{background:var(--ink);color:#fff;border-radius:var(--r-l);padding:30px;display:flex;flex-direction:column;gap:14px}
.side .card .eyebrow{color:rgba(255,255,255,.6)}
.side .card h3{font-family:var(--serif);font-weight:400;font-size:26px;margin:0}
.side .card h3 em{font-style:italic;color:var(--gold)}
.side .card .info-row{display:flex;gap:14px;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.12);font-size:14px}
.side .card .info-row:last-child{border-bottom:0}
.side .card .info-row .ic{width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.06);display:grid;place-items:center;color:var(--gold);flex:none}
.side .card .info-row .k{font-family:var(--mono);font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.55)}
.offices-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-top:28px}
.off{background:var(--paper);border:1px solid var(--line);border-radius:var(--r-l);padding:32px 28px;display:flex;flex-direction:column;gap:10px}
.off .flag{font-family:var(--mono);font-size:10.5px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold-2)}
.off .city{font-family:var(--serif);font-size:38px;line-height:1}
.off .role{font-size:13px;color:var(--ink-3)}
.off .lines{margin-top:12px;font-size:14px;color:var(--ink-2);line-height:1.6;display:flex;flex-direction:column;gap:6px}
.off .hours{font-family:var(--mono);font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-3);margin-top:8px;border-top:1px solid var(--line);padding-top:10px}
.cmap{aspect-ratio:21/7;border-radius:var(--r-l);overflow:hidden;margin-top:8px}
.cmap iframe{width:100%;height:100%;border:0;filter:grayscale(.12)}
@media(max-width:1080px){.quick-row{grid-template-columns:repeat(2,1fr)}.form-grid{grid-template-columns:1fr;gap:40px}.side{position:static}.offices-grid{grid-template-columns:1fr}}
@media(max-width:1080px){.quick-row{grid-template-columns:repeat(2,1fr)}}
@media(max-width:720px){.quick-row{grid-template-columns:1fr}}
.faq-head{max-width:620px;margin-bottom:36px}
.faq-head .section-title{margin:10px 0 0}
.faq-intro{color:var(--ink-2);font-size:16px;line-height:1.6;margin-top:14px}
.faq-grid{display:grid;grid-template-columns:1fr 1fr;gap:36px 56px}
.faq-grid .qa h3{font-family:var(--serif);font-weight:400;font-size:21px;line-height:1.2;margin:0 0 9px}
.faq-grid .qa p{color:var(--ink-2);font-size:14.5px;line-height:1.66;margin:0}
.faq-grid .qa a{color:var(--gold-2);text-decoration:underline;text-underline-offset:2px}
@media(max-width:820px){.faq-grid{grid-template-columns:1fr;gap:26px}}
</style>
@endpush

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'Contact']]"
        h1="Begin the<br/><em>conversation.</em>"
        sub="Whether you are abroad and ready to build securely in Ghana, or local and looking for a trusted manager — reach us by phone, email, WhatsApp, or the form below."/>

    <section class="quick"><div class="container"><div class="quick-row">
        <a class="qitem" href="tel:{{ config('cream.phone.tel') }}"><div class="ic"><x-icon name="phone"/></div><div><div class="k">Phone</div><div class="v">{{ config('cream.phone.display') }}</div><div class="d">By appointment · Mon–Fri</div></div></a>
        <a class="qitem" href="mailto:{{ config('cream.email') }}"><div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div><div><div class="k">Email</div><div class="v" style="font-size:13.5px;white-space:nowrap">{{ config('cream.email') }}</div><div class="d">Replies within 1 business day</div></div></a>
        <a class="qitem" href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener"><div class="ic"><x-icon name="wa"/></div><div><div class="k">WhatsApp</div><div class="v">{{ config('cream.whatsapp.display') }}</div><div class="d">Voice notes accepted</div></div></a>
        <a class="qitem" href="#offices"><div class="ic"><x-icon name="pin"/></div><div><div class="k">Visit HQ</div><div class="v">East Legon, Accra</div><div class="d">Mon–Fri</div></div></a>
    </div></div></section>

    <section class="sec" id="form"><div class="container"><div class="form-grid">
        <div class="form-block">
            <div class="eyebrow"><span class="dot"></span>Send a Message</div>
            <h2>Tell us what you are<br/><em>building, buying,</em> or managing.</h2>
            <p style="color:var(--ink-2);margin:0 0 24px;max-width:54ch">Brief replies arrive within one business day from our Accra office. For diaspora consultations, we schedule a Zoom or WhatsApp call across your time zone.</p>
            <div class="eyebrow" style="margin-bottom:10px">Reason for contact</div>
            <div class="topic-row" id="topics">
                @foreach ($topics as $i => $t)<button class="topic-chip{{ $i === 0 ? ' on' : '' }}" type="button">{{ $t }}</button>@endforeach
            </div>
            @include('public.partials.form-status')
            <form class="cr-form" id="contactForm" action="{{ route('enquiries.store') }}" method="POST" data-enquiry>
                @csrf
                <input type="hidden" name="type" value="contact"/>
                <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
                <input type="hidden" id="topicInput" name="enquiry_type" value="Buy a Property"/>
                <div class="row-2"><div><label>Full name</label><input name="name" type="text" required/></div><div><label>Country / region</label><select name="country"><option>Ghana</option><option>United Kingdom</option><option>United States</option><option>Canada</option><option>Germany</option><option>Nigeria</option><option>Other</option></select></div></div>
                <div class="row-2"><div><label>Email</label><input name="email" type="email" required/></div><div><label>Phone (incl. country code)</label><input name="phone" type="tel" placeholder="+44 / +1 / +233 …"/></div></div>
                <div class="row-2"><div><label>Approx. budget (USD)</label><select name="budget"><option>Under 150K</option><option>150K – 300K</option><option>300K – 500K</option><option>500K – 1M</option><option>1M+</option><option>Not yet defined</option></select></div><div><label>Timeline</label><select name="timeline"><option>Immediate (this quarter)</option><option>3 – 6 months</option><option>6 – 12 months</option><option>12+ months</option><option>Exploring</option></select></div></div>
                <div><label>Message</label><textarea name="message" placeholder="A short brief — bedrooms, neighbourhood, intent (own or invest), and anything else we should know…"></textarea></div>
                <label class="check"><input type="checkbox" name="newsletter" checked/> Keep me informed on signature listings, market notes &amp; the Signature Home Show — quarterly only.</label>
                @include('partials.turnstile')
                <button class="submit" type="submit">Send message <x-icon name="arrow"/></button>
                <div class="form-msg" style="display:none"></div>
                <p class="privacy-note">By submitting you agree to our <a href="/privacy">privacy notice</a>.</p>
            </form>
        </div>
        <aside class="side">
            <div class="card">
                <div class="eyebrow"><span class="dot"></span>Direct line · CEO</div>
                <h3>Adeline <em>Quarshie</em></h3>
                <div style="font-size:13px;color:rgba(255,255,255,.65)">CEO · Credence Holdings Global</div>
                <div style="display:flex;gap:8px;margin-top:8px">
                    <a class="btn-ghost" style="flex:1;justify-content:center;color:#fff;border-color:rgba(255,255,255,.3)" href="tel:{{ config('cream.phone.tel') }}">Call</a>
                    <a class="btn-ghost" style="flex:1;justify-content:center;color:#fff;border-color:rgba(255,255,255,.3)" href="mailto:{{ config('cream.email') }}">Email</a>
                    <a class="btn-ghost" style="flex:1;justify-content:center;color:#fff;border-color:rgba(255,255,255,.3)" href="https://wa.me/{{ config('cream.whatsapp.number') }}" target="_blank" rel="noopener">WhatsApp</a>
                </div>
            </div>
            <div class="card">
                <div class="eyebrow"><span class="dot"></span>Diaspora Programme</div>
                <h3>For clients in the<br/><em>USA &amp; UK.</em></h3>
                <div class="info-row"><div class="ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div><div><div class="k">Scheduled call</div><div>Zoom / WhatsApp · 30 min</div></div></div>
                <div class="info-row"><div class="ic"><x-icon name="pin"/></div><div><div class="k">Time zones</div><div>EST · PST · GMT · WAT</div></div></div>
                <div class="info-row"><div class="ic"><x-icon name="check"/></div><div><div class="k">Includes</div><div>Brief intake · property longlist · price guide</div></div></div>
            </div>
        </aside>
    </div></div></section>

    <section class="sec bone2" id="offices"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Our Base</div><h2 class="section-title">Accra HQ and<br/>diaspora desks.</h2></div><div class="right">We execute on the ground in Ghana, and meet diaspora clients by appointment in the UK and USA — no public office there.</div></div>
        <div class="offices-grid">
            <div class="off"><span class="flag">Ghana · Headquarters</span><div class="city">Accra</div><div class="role">East Legon, Greater Accra</div><div class="lines"><div><b>Bawuah Junction, East Legon</b></div><div>Greater Accra, Ghana</div><div><a href="tel:{{ config('cream.phone.tel') }}">{{ config('cream.phone.display') }}</a></div><div><a href="mailto:{{ config('cream.email') }}">{{ config('cream.email') }}</a></div></div><div class="hours">Mon–Fri</div></div>
            <div class="off"><span class="flag">United Kingdom · Diaspora desk</span><div class="city">London</div><div class="role">By appointment</div><div class="lines"><div>Diaspora consultations &amp; portfolio reviews, scheduled via Accra HQ.</div><div>Zoom &amp; WhatsApp</div></div><div class="hours">By appointment · GMT &amp; BST</div></div>
            <div class="off"><span class="flag">United States · Holdings liaison</span><div class="city">Manchester, CT</div><div class="role">Credence Holdings LLC</div><div class="lines"><div>805 Horizon Way, Manchester CT 06042</div><div>North American diaspora, by appointment.</div></div><div class="hours">By appointment · EST &amp; PST</div></div>
        </div>
        <div class="cmap"><iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Credence RE — East Legon, Accra" src="https://maps.google.com/maps?q={{ config('cream.address.lat') }},{{ config('cream.address.lng') }}&t=&ie=UTF8&output=embed"></iframe></div>
    </div></section>

    <section class="sec bone" id="faq"><div class="container">
        <div class="faq-head">
            <div class="eyebrow"><span class="dot"></span>Common Questions</div>
            <h2 class="section-title">What to ask<br/>on a first call.</h2>
            <p class="faq-intro">A few of the questions clients ask most often, with our short answers. Bring more on the call — we welcome them.</p>
        </div>
        <div class="faq-grid">
            <div class="qa"><h3>Do you work with diaspora buyers?</h3><p>Yes — it's our specialism. We provide end-to-end service from land due diligence and title verification through construction, interior finishing, and ongoing management for clients in the USA, UK, and beyond.</p></div>
            <div class="qa"><h3>How are properties verified?</h3><p>Every property we list has been title-searched at the Lands Commission, independently surveyed, and signed off by retained counsel. We share full documentation on confirmed enquiry.</p></div>
            <div class="qa"><h3>Can I tour a property remotely?</h3><p>Absolutely. We produce live walkthroughs via WhatsApp video, full cinematic tours for shortlisted homes, and arrange in-person tours for visiting family or counsel on your behalf.</p></div>
            <div class="qa"><h3>What does property management include?</h3><p>Tenant sourcing &amp; vetting, rent collection, 24-hour maintenance, monthly statements, quarterly condition reports, and a dedicated account manager who knows your property by name.</p></div>
            <div class="qa"><h3>Do you build on land I already own?</h3><p>Yes. We provide standalone construction &amp; project management for clients with existing land — from concept through handover, with weekly site reporting throughout.</p></div>
            <div class="qa"><h3>What payment options do you offer?</h3><p>Two flexible routes — outright (out-of-pocket) payment in line with the agreed terms, or mortgage financing through our partner banks in Ghana for eligible residents and diaspora clients. Developments also use staged payments tied to verifiable milestones, with escrow available on request. <a href="/insights#payment-options">More on flexible payment options →</a></p></div>
        </div>
    </div></section>
@endsection
