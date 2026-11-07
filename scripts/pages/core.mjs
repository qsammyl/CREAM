import * as C from '../components.mjs';
import { pageHero } from './_util.mjs';

/* ---------------- SERVICES ---------------- */
const SERVICES = [
  {
    id: 'development', n: '01', title: 'Real Estate<br/><em>Development.</em>', img: 'assets/beverly-hills.jpeg', tag: 'Case Study', alt: 'Beverly Homes',
    p: ['We develop modern residential and commercial properties that combine luxury, functionality, and long-term value. From greenfield enclaves to single-lot homes, every project is led by an owner-operator team that stays accountable from concept to handover.',
      'Our developments are designed around how people actually live — open ground floors, ensuite bedrooms, private outdoor rooms — and built to specifications you can audit at every stage.'],
    del: ['Concept design + brief development', 'Planning consent & permit liaison', 'Build-cost modelling & reserves', 'Marketing & sales programme', 'Handover & aftercare', 'HOA & estate management setup']
  },
  {
    id: 'sales', n: '02', title: 'Property Sales<br/><em>& Investment.</em>', img: 'assets/royal-manhattan.png', tag: 'Diaspora Programme', alt: 'Royal Manhattan', reverse: true,
    p: ['Whether you are purchasing your dream home, buying land, or investing in income-generating properties — we guide you through every step with professionalism and transparency.',
      'Our advisory team verifies title, models yields, and negotiates on your behalf. For diaspora buyers we offer video walkthroughs, escrow coordination, and a single point of contact across UK, US, and Ghana time zones.'],
    del: ['Curated listing access', 'Title & document verification', 'Yield & appreciation modelling', 'Negotiation & closing', 'Video walk-throughs (diaspora)', 'Escrow & settlement coordination']
  },
  {
    id: 'management', n: '03', title: 'Property<br/><em>Management.</em>', img: '', tag: 'Live Service', alt: 'Managed home', fromPrice: true,
    p: ['We manage residential and commercial properties with efficiency and care — ensuring properties remain profitable, secure, and well-maintained, while owners enjoy clarity over income, occupancy, and condition.',
      'Whether you own one home or a small portfolio, you receive monthly statements, photographic condition reports, and a single account manager who knows your property by name.'],
    del: ['Tenant sourcing & vetting', 'Rent collection & reconciliation', '24-hour maintenance response', 'Monthly owner statements', 'Quarterly condition reports', 'HOA & estate fees handling']
  },
  {
    id: 'construction', n: '04', title: 'Construction &<br/><em>Project Management.</em>', img: '', tag: 'On Site', alt: 'Construction site', dark: true, reverse: true,
    p: ['From foundation to finishing, we oversee construction projects with attention to detail, quality control, and timely delivery. Weekly site reports, audit trails on every variation, and a project manager you can reach by name.',
      'For diaspora clients, our reporting is built to give you the same line-of-sight as if you walked the site each Saturday yourself.'],
    del: ['Schedule & cost discipline', 'Trade procurement & vetting', 'Weekly progress reports', 'QA walk-throughs & snag lists', 'Variation order audit trail', 'Handover & defects period']
  },
  {
    id: 'interiors', n: '05', title: 'Interior Decor<br/><em>& Styling.</em>', img: 'assets/interior-decor.jpg', tag: 'Credence Interiors', alt: 'Styled interior',
    p: ['Through premium interior solutions, we transform spaces into elegant and functional environments that reflect style and sophistication. From full furnishings on a turn-key build to targeted re-styling of a single room.',
      'Delivered by Credence Interiors, our in-house specialist arm — for clients who want one team responsible from drywall to drapes.'],
    del: ['Concept boards & brief', 'FF&E specification & procurement', 'Bespoke joinery & soft goods', 'Art & accessories curation', 'Install & styling day', 'Photography & reveal']
  },
  {
    id: 'land', n: '06', title: 'Land Acquisition<br/><em>& Due Diligence.</em>', img: '', tag: 'Due Diligence', alt: 'Land', reverse: true, fromPrice: true,
    p: ['We assist clients with land verification, documentation checks, survey coordination, and legal guidance to ensure safe and secure property transactions. The single most important step we take for diaspora clients.',
      'Every plot we present has been verified through the Lands Commission, surveyed independently, and signed off by our retained counsel — no exceptions.'],
    del: ['Title search at Lands Commission', 'Independent boundary survey', 'Chief / family verification', 'Legal counsel sign-off', 'Indenture & registration', 'Ongoing site monitoring']
  }
];

const services = {
  path: 'services.html',
  build(ctx) {
    const { site } = ctx;
    const blocks = SERVICES.map((s) => {
      const del = s.del.map((d) => `<div class="d"><span class="tick">${C.icon.check}</span>${d}</div>`).join('');
      const media = s.img
        ? `<img src="${s.img}" alt="${C.esc(s.alt)}"/>`
        : C.placeholder(0, s.alt) + `\n        <!-- TODO(sam): supply a real photo for the ${s.id} service. The old page used an Unsplash stock photo. -->`;
      const info = `<div class="info">
        <div class="eyebrow"><span class="dot"></span>Service</div>
        <h2>${s.title}</h2>
        ${s.p.map((x) => `<p>${C.esc(x)}</p>`).join('')}
        ${s.fromPrice ? `<p style="font-family:var(--mono);font-size:12px;letter-spacing:.1em;color:var(--gold-2)">From GHS ___ · <!-- TODO(sam): add a real "from" price for ${s.id} -->price on request</p>` : ''}
        <div class="deliverables">${del}</div>
        <a class="btn-gold" style="margin-top:28px" href="contact.html?reason=${s.id}">Enquire about this service ${C.icon.arrow}</a>
      </div>`;
      const imgblk = `<div class="imgblk"><span class="tag">${C.esc(s.tag)}</span>${media}</div>`;
      return `<section class="svc-block${s.dark ? ' dark' : ''}${s.reverse ? ' reverse' : ''}" id="${s.id}">
  <div class="container"><div class="svc-grid">${s.reverse ? imgblk + info : info + imgblk}</div></div>
</section>`;
    }).join('\n');

    const css = `.svc-block{padding:clamp(64px,8vw,120px) 0;border-bottom:1px solid var(--line);scroll-margin-top:120px}
.svc-block:nth-child(odd){background:var(--bone)}.svc-block:nth-child(even){background:var(--bone-2)}
.svc-block.dark{background:#0F0E0C;color:#fff;border-bottom-color:rgba(255,255,255,.1)}
.svc-block.dark p,.svc-block.dark .eyebrow{color:rgba(255,255,255,.8)}
.svc-grid{display:grid;grid-template-columns:1fr 1.1fr;gap:70px;align-items:center}
.svc-block.reverse .svc-grid .info{order:2}.svc-block.reverse .svc-grid .imgblk{order:1}
.svc-grid .info h2{font-family:var(--serif);font-weight:400;font-size:clamp(34px,4vw,60px);line-height:1.02;letter-spacing:-0.015em;margin:14px 0 20px}
.svc-grid .info h2 em{font-style:italic;color:var(--gold)}
.svc-grid .info p{font-size:16.5px;line-height:1.7;color:var(--ink-2);margin:0 0 16px}
.deliverables{margin-top:26px;display:grid;grid-template-columns:1fr 1fr;gap:12px 26px}
.deliverables .d{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--ink-2);line-height:1.45}
.deliverables .d .tick{width:18px;height:18px;border-radius:50%;background:var(--emerald);color:#fff;display:grid;place-items:center;flex:none;margin-top:1px}
.svc-block.dark .deliverables .d{color:rgba(255,255,255,.82)}.svc-block.dark .deliverables .d .tick{background:var(--gold);color:var(--ink)}
.imgblk{position:relative;aspect-ratio:4/5;border-radius:var(--r-l);overflow:hidden;background:var(--emerald-2);box-shadow:var(--shadow-3)}
.imgblk img{width:100%;height:100%;object-fit:cover}
.imgblk .tag{position:absolute;top:18px;left:18px;background:rgba(255,255,255,.92);backdrop-filter:blur(8px);padding:8px 14px;border-radius:999px;font-family:var(--mono);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink);z-index:2}
@media(max-width:1080px){.svc-grid,.svc-block.reverse .svc-grid{grid-template-columns:1fr;gap:36px}.svc-block.reverse .svc-grid .info{order:2}.svc-block.reverse .svc-grid .imgblk{order:1}.imgblk{aspect-ratio:4/3}}
@media(max-width:720px){.deliverables{grid-template-columns:1fr}}`;

    const jump = `<section class="jumpbar"><div class="inner">
  ${SERVICES.map((s) => `<a href="#${s.id}">${s.title.replace(/<[^>]+>/g, '').replace('.', '')}</a>`).join('\n  ')}
</div></section>`;

    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Services' }], h1: 'Six disciplines,<br/><em>one trusted brand.</em>', sub: 'From foundation to finishing, acquisition to management — Credence handles every stage of the real estate lifecycle under one roof.' })}
${jump}
${blocks}
<section class="sec ink" style="text-align:center"><div class="container" style="max-width:760px">
  <div class="eyebrow" style="color:rgba(255,255,255,.7);justify-content:center"><span class="dot"></span>Begin the Conversation</div>
  <h2 class="t" style="color:#fff;margin:14px 0 18px">Ready to build, buy,<br/><em>invest, or manage?</em></h2>
  <p class="prose" style="margin:0 auto 28px;color:rgba(255,255,255,.75)">Partner with Credence — the discipline of a global firm with the local knowledge of an Accra-born team.</p>
  <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap"><a class="btn-gold" href="contact.html">Book a consultation ${C.icon.arrow}</a><a class="btn-ghost" style="color:#fff;border-color:rgba(255,255,255,.3)" href="properties.html">Browse properties</a></div>
</div></section>`;

    return C.page(site, {
      d: 0, active: 'services',
      title: 'Services — Development, Sales, Management & Land | Credence Real Estate',
      description: 'Six real estate disciplines under one roof: development, sales & investment, property management, construction, interiors, and land due diligence in Accra.',
      canonical: 'services.html', ogImage: 'assets/beverly-hills.jpeg',
      extraCss: css, body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- CONTACT ---------------- */
const contact = {
  path: 'contact.html',
  build(ctx) {
    const { site } = ctx;
    const topics = ['Buy a Property', 'Sell a Property', 'Build with Credence', 'Property Management', 'Interior Decor', 'Land & Due Diligence', 'Diaspora Programme', 'Press / Other'];
    const css = `.quick{background:var(--bone);padding:40px 0;border-bottom:1px solid var(--line)}
.quick-row{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
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
@media(max-width:720px){.quick-row{grid-template-columns:1fr}}`;

    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Contact' }], h1: 'Begin the<br/><em>conversation.</em>', sub: "Whether you are abroad and ready to build securely in Ghana, or local and looking for a trusted manager — reach us by phone, email, WhatsApp, or the form below." })}
<section class="quick"><div class="container"><div class="quick-row">
  <a class="qitem" href="tel:${site.phone.tel}"><div class="ic">${C.icon.phone}</div><div><div class="k">Phone</div><div class="v">${C.esc(site.phone.display)}</div><div class="d">By appointment · Mon–Sat</div></div></a>
  <a class="qitem" href="mailto:${site.email}"><div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div><div><div class="k">Email</div><div class="v" style="font-size:14px;word-break:break-all">${C.esc(site.email)}</div><div class="d">Replies within 1 business day</div></div></a>
  <a class="qitem" href="https://wa.me/${site.whatsapp.number}" target="_blank" rel="noopener"><div class="ic">${C.icon.wa}</div><div><div class="k">WhatsApp</div><div class="v">${C.esc(site.whatsapp.display)}</div><div class="d">Voice notes accepted</div></div></a>
  <a class="qitem" href="#offices"><div class="ic">${C.icon.pin}</div><div><div class="k">Visit HQ</div><div class="v">East Legon, Accra</div><div class="d">By appointment only</div></div></a>
</div></div></section>

<section class="sec" id="form"><div class="container"><div class="form-grid">
  <div class="form-block">
    <div class="eyebrow"><span class="dot"></span>Send a Message</div>
    <h2>Tell us what you are<br/><em>building, buying,</em> or managing.</h2>
    <p style="color:var(--ink-2);margin:0 0 24px;max-width:54ch">Brief replies arrive within one business day from our Accra office. For diaspora consultations, we schedule a Zoom or WhatsApp call across your time zone.</p>
    <div class="eyebrow" style="margin-bottom:10px">Reason for contact</div>
    <div class="topic-row" id="topics">
      ${topics.map((t, i) => `<button class="topic-chip${i === 0 ? ' on' : ''}" type="button">${C.esc(t)}</button>`).join('\n      ')}
    </div>
    <form class="cr-form" id="contactForm" action="https://api.web3forms.com/submit" method="POST" data-web3>
      <input type="hidden" name="access_key" value="${site.web3forms.contactKey}"/>
      <input type="hidden" name="subject" value="New Enquiry — Credence Real Estate"/>
      <input type="hidden" name="from_name" value="Credence Website"/>
      <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
      <input type="hidden" id="topicInput" name="enquiry_type" value="Buy a Property"/>
      <div class="row-2"><div><label>Full name</label><input name="name" type="text" required/></div><div><label>Country / region</label><select name="country"><option>Ghana</option><option>United Kingdom</option><option>United States</option><option>Canada</option><option>Germany</option><option>Nigeria</option><option>Other</option></select></div></div>
      <div class="row-2"><div><label>Email</label><input name="email" type="email" required/></div><div><label>Phone (incl. country code)</label><input name="phone" type="tel" placeholder="+44 / +1 / +233 …"/></div></div>
      <div class="row-2"><div><label>Approx. budget (USD)</label><select name="budget"><option>Under 150K</option><option>150K – 300K</option><option>300K – 500K</option><option>500K – 1M</option><option>1M+</option><option>Not yet defined</option></select></div><div><label>Timeline</label><select name="timeline"><option>Immediate (this quarter)</option><option>3 – 6 months</option><option>6 – 12 months</option><option>12+ months</option><option>Exploring</option></select></div></div>
      <div><label>Message</label><textarea name="message" placeholder="A short brief — bedrooms, neighbourhood, intent (own or invest), and anything else we should know…"></textarea></div>
      <label class="check"><input type="checkbox" name="newsletter" checked/> Keep me informed on signature listings, market notes &amp; the Signature Home Show — quarterly only.</label>
      <button class="submit" type="submit">Send message ${C.icon.arrow}</button>
      <div class="form-msg" style="display:none"></div>
      <p class="privacy-note">By submitting you agree to our <a href="privacy.html">privacy notice</a>.</p>
    </form>
  </div>
  <aside class="side">
    <div class="card">
      <div class="eyebrow"><span class="dot"></span>Direct line · CEO</div>
      <h3>Adeline <em>Quarshie</em></h3>
      <div style="font-size:13px;color:rgba(255,255,255,.65)">CEO · Credence Holdings Global</div>
      <div style="display:flex;gap:8px;margin-top:8px">
        <a class="btn-ghost" style="flex:1;justify-content:center;color:#fff;border-color:rgba(255,255,255,.3)" href="tel:${site.phone.tel}">Call</a>
        <a class="btn-ghost" style="flex:1;justify-content:center;color:#fff;border-color:rgba(255,255,255,.3)" href="mailto:${site.email}">Email</a>
        <a class="btn-ghost" style="flex:1;justify-content:center;color:#fff;border-color:rgba(255,255,255,.3)" href="https://wa.me/${site.whatsapp.number}" target="_blank" rel="noopener">WhatsApp</a>
      </div>
    </div>
    <div class="card">
      <div class="eyebrow"><span class="dot"></span>Diaspora Programme</div>
      <h3>For clients in the<br/><em>USA &amp; UK.</em></h3>
      <div class="info-row"><div class="ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div><div><div class="k">Scheduled call</div><div>Zoom / WhatsApp · 30 min</div></div></div>
      <div class="info-row"><div class="ic">${C.icon.pin}</div><div><div class="k">Time zones</div><div>EST · PST · GMT · WAT</div></div></div>
      <div class="info-row"><div class="ic">${C.icon.check}</div><div><div class="k">Includes</div><div>Brief intake · property longlist · price guide</div></div></div>
    </div>
  </aside>
</div></div></section>

<section class="sec bone2" id="offices"><div class="container">
  <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Our Base</div><h2 class="section-title">Accra HQ and<br/>diaspora desks.</h2></div><div class="right">We execute on the ground in Ghana, and meet diaspora clients by appointment in the UK and USA — no public office there.</div></div>
  <div class="offices-grid">
    <div class="off"><span class="flag">Ghana · Headquarters</span><div class="city">Accra</div><div class="role">East Legon, Greater Accra</div><div class="lines"><div><b>Bawuah Junction, East Legon</b></div><div>Greater Accra, Ghana</div><div><a href="tel:${site.phone.tel}">${C.esc(site.phone.display)}</a></div><div><a href="mailto:${site.email}">${C.esc(site.email)}</a></div></div><div class="hours">By appointment · Mon–Sat<br/><!-- TODO(sam): confirm exact opening hours --></div></div>
    <div class="off"><span class="flag">United Kingdom · Diaspora desk</span><div class="city">London</div><div class="role">By appointment</div><div class="lines"><div>Diaspora consultations &amp; portfolio reviews, scheduled via Accra HQ.</div><div>Zoom &amp; WhatsApp</div></div><div class="hours">By appointment · GMT &amp; BST</div></div>
    <div class="off"><span class="flag">United States · Holdings liaison</span><div class="city">Manchester, CT</div><div class="role">Credence Holdings LLC</div><div class="lines"><div>805 Horizon Way, Manchester CT 06042</div><div>North American diaspora, by appointment.</div></div><div class="hours">By appointment · EST &amp; PST</div></div>
  </div>
  <div class="cmap"><iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Credence RE — East Legon, Accra" src="https://maps.google.com/maps?q=${site.address.coords.lat},${site.address.coords.lng}&t=&ie=UTF8&output=embed"></iframe></div>
</div></section>`;

    return C.page(site, {
      d: 0, active: 'contact',
      title: 'Contact — Book a Consultation | Credence Real Estate',
      description: 'Contact Credence Real Estate in Accra by phone, WhatsApp, email or form. Diaspora consultations by Zoom across UK and US time zones.',
      canonical: 'contact.html', ogImage: 'assets/royal-manhattan.png',
      extraCss: css, body, includePopup: false
    });
  }
};

/* ---------------- ABOUT ---------------- */
const about = {
  path: 'about.html',
  build(ctx) {
    const { site } = ctx;
    const css = `.story-grid{display:grid;grid-template-columns:1fr 1.4fr;gap:70px;align-items:start}
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
@media(max-width:640px){.group-grid,.team-grid{grid-template-columns:1fr}}`;

    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'About' }], h1: 'A diversified holding,<br/><em>anchored in integrity.</em>', sub: 'Credence Real Estate & Asset Management is a premium real estate and property solutions company operating under Credence Holdings Global — with operations in the USA, the United Kingdom, and Ghana.' })}
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
  <div class="ceo-img"><span class="badge">CEO · Credence Holdings Global</span><img src="assets/adeline-quarshie.jpeg" alt="Adeline Quarshie, Chief Executive Officer"/></div>
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
    <div class="tm"><div class="ph"><img src="assets/adeline-quarshie.jpeg" alt="Adeline Quarshie" style="object-position:center 22%"/></div><div class="nm">Adeline Quarshie</div><div class="rl">Chief Executive Officer</div></div>
    <div class="tm"><div class="ph"><img src="assets/joshua-asimakumah.jpeg" alt="Joshua Asimakumah"/></div><div class="nm">Joshua Asimakumah</div><div class="rl">Head of Administration</div></div>
    <div class="tm"><div class="ph"><img src="assets/adelina-quarshie.jpeg" alt="Adelina Quarshie"/></div><div class="nm">Adelina Quarshie</div><div class="rl">Head of Diaspora</div></div>
  </div>
  <img src="assets/group-photo.jpeg" alt="The Credence team at the Accra office" loading="lazy" style="margin-top:44px;width:100%;border-radius:var(--r-l);display:block;object-fit:cover;aspect-ratio:16/6"/>
</div></section>

<section class="sec bone"><div class="container" style="text-align:center">
  <div class="eyebrow" style="justify-content:center"><span class="dot"></span>Series</div>
  <h2 class="t" style="margin:12px auto 14px;max-width:16ch">The Signature <em>Home Show</em></h2>
  <p class="prose" style="margin:0 auto 24px;color:var(--ink-2)">Each episode steps inside a Credence home — the owners, the brief, the build. Watch on YouTube and read more on our Insights hub.</p>
  <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap"><a class="btn-solid" href="${site.show.channel}" target="_blank" rel="noopener">Watch on YouTube ${C.icon.arrow}</a><a class="btn-ghost" href="insights.html">Insights hub</a></div>
</div></section>`;

    return C.page(site, {
      d: 0, active: 'about',
      title: 'About — Credence Real Estate & Asset Management',
      description: 'Credence Real Estate & Asset Management, part of Credence Holdings Global, serves diaspora and local clients in Ghana with integrity, governance and end-to-end service.',
      canonical: 'about.html', ogImage: 'assets/adeline-quarshie.jpeg',
      extraCss: css, body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

export const corePages = [services, contact, about];
