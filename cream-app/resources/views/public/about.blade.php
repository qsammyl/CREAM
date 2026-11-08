@extends('public.layout')

@push('head')
<style>
.story-grid{display:grid;grid-template-columns:1fr 1.4fr;gap:70px;align-items:start}
.story-grid h2{font-family:var(--serif);font-weight:400;font-size:clamp(32px,3.8vw,52px);line-height:1.04;margin:14px 0 0}
.story-grid p{font-size:16.5px;line-height:1.75;color:var(--ink-2);margin:0 0 20px}
.story-grid p:first-of-type::first-letter{font-family:var(--serif);font-size:62px;line-height:.85;float:left;margin:6px 14px 0 0;color:var(--emerald)}
.vm-grid{display:grid;grid-template-columns:1fr 1fr;gap:28px}
.vmcard{background:var(--paper);border:1px solid var(--line);border-radius:var(--r-l);padding:44px 38px;display:flex;flex-direction:column;gap:14px}
.vmcard h3{font-family:var(--serif);font-weight:400;font-size:32px;margin:0;line-height:1.1}
.vmcard h3 em{font-style:italic;color:var(--gold)}
.vmcard p{font-size:16px;line-height:1.7;color:var(--ink-2);margin:0}
.ceo-block{background:#0F0E0C;color:#fff}
.ceo-block .grid{display:grid;grid-template-columns:1.1fr 1.4fr;gap:70px;align-items:start}
.ceo-img{position:relative;aspect-ratio:4/5;border-radius:var(--r-l);overflow:hidden;background:#222;box-shadow:var(--shadow-3)}
.ceo-img img{width:100%;height:100%;object-fit:cover;object-position:center 22%}
.ceo-img .badge{position:absolute;top:18px;left:18px;background:rgba(255,255,255,.1);backdrop-filter:blur(8px);padding:8px 14px;border-radius:999px;font-family:var(--mono);font-size:11px;letter-spacing:.14em;color:#fff}
.ceo-block h2{font-family:var(--serif);font-weight:400;font-size:clamp(34px,4vw,56px);line-height:1.04;margin:14px 0 28px;color:#fff}
.ceo-block h2 em{font-style:italic;color:var(--gold)}
.ceo-block .body p{font-size:16px;line-height:1.8;color:rgba(255,255,255,.78);margin:0 0 16px}
.ceo-block .pull{font-family:var(--serif);font-size:24px;font-style:italic;line-height:1.4;color:#fff;margin:22px 0;padding:0 0 0 26px;border-left:2px solid var(--gold)}
.ceo-block .sig{margin-top:30px;padding-top:22px;border-top:1px solid rgba(255,255,255,.15)}
.ceo-block .sig .name{font-family:var(--serif);font-style:italic;font-size:30px}
.ceo-block .sig .role{font-family:var(--mono);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.6);margin-top:6px}
.group-grid,.team-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-top:28px}
.gcard{background:var(--paper);border:1px solid var(--line);border-radius:var(--r-l);padding:30px 26px;display:flex;flex-direction:column;gap:8px}
.gcard.cur{background:var(--ink);color:#fff;border-color:var(--ink)}
.gcard .tag{font-family:var(--mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-2)}
.gcard.cur .tag{color:var(--gold)}
.gcard .nm{font-family:var(--serif);font-size:23px;line-height:1.1;margin-top:8px}.gcard .nm em{font-style:italic;color:var(--gold)}
.gcard .d{font-size:13px;color:var(--ink-3);line-height:1.55}.gcard.cur .nm{color:#fff}.gcard.cur .d{color:rgba(255,255,255,.7)}
.tm .ph{aspect-ratio:4/5;background:var(--bone-3);border-radius:var(--r-m);overflow:hidden}
.tm .ph img{width:100%;height:100%;object-fit:cover}
.tm .nm{font-family:var(--serif);font-size:22px;margin-top:12px}.tm .rl{font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--ink-3)}
@media(max-width:1080px){.story-grid{grid-template-columns:1fr;gap:28px}.vm-grid{grid-template-columns:1fr}.ceo-block .grid{grid-template-columns:1fr;gap:36px}.group-grid,.team-grid{grid-template-columns:1fr 1fr}}
@media(max-width:640px){.group-grid,.team-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    <x-page-hero :crumbs="[['label' => 'Credence', 'href' => '/'], ['label' => 'About']]"
        h1="A diversified holding,<br/><em>anchored in integrity.</em>"
        sub="Credence Real Estate &amp; Asset Management is a premium real estate and property solutions company operating under Credence Holdings Global — with operations in the USA, the United Kingdom, and Ghana."/>

    <section class="sec bone"><div class="container"><div class="story-grid">
        <div><div class="eyebrow"><span class="dot"></span>Our Story</div><h2>Significance,<br/>not size.</h2></div>
        <div>
            <p>We began with a commitment to credibility. In markets where trust defines success, we chose to build enterprises anchored in integrity, governance, and measurable value creation. That commitment remains the foundation of everything we do today.</p>
            <p>Beyond serving the diaspora community, we work closely with high-net-worth individuals, investors, and homeowners seeking premium development and property management services. Our team is committed to creating exceptional residential and commercial spaces that combine luxury, functionality, and long-term value.</p>
            <p>At Credence, we understand that building a property is more than construction — it is about creating legacy, preserving wealth, and delivering comfort. This is why we manage every project with attention to detail, integrity, and excellence.</p>
            <p>Whether you are abroad looking to build securely in Ghana, an investor seeking reliable property management, or a local client pursuing your dream home — Credence is your trusted partner every step of the way.</p>
        </div>
    </div></div></section>

    <section class="sec bone2"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Vision &amp; Mission</div><h2 class="section-title">Two statements,<br/>one direction.</h2></div><div class="right">A clear vision keeps the firm pointed forward; a disciplined mission keeps the team accountable to the clients in front of us today.</div></div>
        <div class="vm-grid">
            <div class="vmcard"><div class="eyebrow">Our Vision</div><h3>To become one of <em>Africa's most trusted</em> global real estate brands.</h3><p>Connecting the diaspora and local communities to exceptional property opportunities in Ghana — by setting the standard for transparency, quality, and stewardship in the markets we serve.</p></div>
            <div class="vmcard"><div class="eyebrow">Our Mission</div><h3>To deliver world-class real estate, construction &amp; asset management — <em>with integrity.</em></h3><p>Through innovation, professionalism, and integrity — helping clients build lasting value and legacy across residential and commercial property in Ghana and beyond.</p></div>
        </div>
    </div></section>

    <section class="ceo-block sec"><div class="container"><div class="grid">
        <div class="ceo-img"><span class="badge">CEO · Credence Holdings Global</span><img src="/assets/adeline-quarshie.jpeg" alt="Adeline Quarshie, Chief Executive Officer"/></div>
        <div>
            <div class="eyebrow" style="color:rgba(255,255,255,.65)"><span class="dot"></span>Chairman / CEO Message</div>
            <h2>Our journey has never been<br/>about size — it has always<br/>been about <em>significance.</em></h2>
            <div class="body">
                <p>We began with a commitment to credibility. In markets where trust defines success, we chose to build enterprises anchored in integrity, governance, and measurable value creation. That commitment remains the foundation of everything we do today.</p>
                <p>As a diversified global holding company, we operate across financial services, real estate, consultancy, and lifestyle enterprises with a disciplined approach to growth. Each subsidiary is structured for performance, accountability, and long-term sustainability.</p>
                <p>Our ambition is clear: to build institutions that outlive trends and create generational impact.</p>
                <div class="pull">We believe strong leadership demands vision, but also responsibility — to clients, partners, employees, and the communities we serve. Growth without structure is noise. Growth with governance is legacy.</div>
                <p>As we expand our global footprint, we remain guided by excellence, innovation, and an unwavering commitment to delivering value. To our partners and stakeholders, thank you for your trust. To future collaborators, we welcome strategic conversations.</p>
            </div>
            <div class="sig"><div class="name">Adeline Quarshie</div><div class="role">Chief Executive Officer · Credence Holdings Global</div></div>
        </div>
    </div></div></section>

    <section class="sec bone"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>The Credence Group</div><h2 class="section-title">A diversified<br/>global holding.</h2></div><div class="right">Credence Holdings Global operates across financial services, real estate, consultancy, and lifestyle enterprises. Each subsidiary is structured for performance and accountability.</div></div>
        <div class="group-grid">
            <div class="gcard cur"><span class="tag">You are here</span><div class="nm">Real Estate &amp;<br/><em>Asset Mgt.</em></div><div class="d">Development, sales, management, construction, interiors, land due-diligence — Ghana and diaspora.</div></div>
            <div class="gcard"><span class="tag">Financial Services</span><div class="nm">Credence<br/>Micro-Credit</div><div class="d">Credit and financial services calibrated to Ghana's small-business and household segments.</div></div>
            <div class="gcard"><span class="tag">Lifestyle</span><div class="nm">Credence<br/>Interiors</div><div class="d">Premium interior decoration and styling — residential, commercial, and hospitality projects.</div></div>
            <div class="gcard"><span class="tag">Consultancy</span><div class="nm">Business<br/>Consulting</div><div class="d">Strategy, governance, and operational consulting for owners, investors, and family offices.</div></div>
        </div>
    </div></section>

    <section class="sec bone2"><div class="container">
        <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>The Team</div><h2 class="section-title">A small, deliberate<br/>group of operators.</h2></div><div class="right">Compact teams, senior people, repeatable processes. <em class="serif">Further bios to follow.</em></div></div>
        <div class="team-grid">
            <div class="tm"><div class="ph"><img src="/assets/adeline-quarshie.jpeg" alt="Adeline Quarshie" style="object-position:49% 35%;transform:scale(1.24);transform-origin:49% 35%"/></div><div class="nm">Adeline Quarshie</div><div class="rl">Chief Executive Officer</div></div>
            <div class="tm"><div class="ph"><img src="/assets/ivan-avereyireh.jpeg" alt="Ivan Avereyireh" style="object-position:50% 42%;transform:scale(1.08);transform-origin:50% 42%"/></div><div class="nm">Ivan Avereyireh</div><div class="rl">Executive Director</div></div>
            <div class="tm"><div class="ph"><img src="/assets/joshua-asimakumah.jpeg" alt="Joshua Asimakumah" style="object-position:49% 25%;transform:scale(1.98);transform-origin:49% 25%"/></div><div class="nm">Joshua Asimakumah</div><div class="rl">Head of Administration</div></div>
            <div class="tm"><div class="ph"><img src="/assets/adelina-quarshie.jpeg" alt="Adelina Quarshie" style="object-position:54% 33%;transform:scale(1.18);transform-origin:54% 33%"/></div><div class="nm">Adelina Quarshie</div><div class="rl">Head of Diaspora</div></div>
        </div>
        <img src="/assets/group-photo.jpeg" alt="The Credence team at the Accra office" loading="lazy" style="margin-top:44px;width:100%;border-radius:var(--r-l);display:block;object-fit:cover;aspect-ratio:16/6"/>
    </div></section>

    <section class="sec bone"><div class="container" style="text-align:center">
        <div class="eyebrow" style="justify-content:center"><span class="dot"></span>Series</div>
        <h2 class="t" style="margin:12px auto 14px;max-width:16ch">The Signature <em>Home Show</em></h2>
        <p class="prose" style="margin:0 auto 24px;color:var(--ink-2)">Each episode steps inside a Credence home — the owners, the brief, the build. Watch on YouTube and read more on our Insights hub.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap"><a class="btn-solid" href="{{ config('cream.show.channel') }}" target="_blank" rel="noopener">Watch on YouTube <x-icon name="arrow"/></a><a class="btn-ghost" href="/insights">Insights hub</a></div>
    </div></section>
@endsection
