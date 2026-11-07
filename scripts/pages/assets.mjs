import * as C from '../components.mjs';
import { grid, filterbar, pageHero } from './_util.mjs';

/* shared CTA used on grid pages */
function offMarketCTA(site, d = 0) {
  const p = C.P(d);
  return `<section class="sec bone" id="off-market">
  <div class="container" style="text-align:center">
    <div class="eyebrow" style="justify-content:center"><span class="dot"></span>Cannot find the right fit?</div>
    <h2 class="t" style="margin:14px auto 16px;max-width:16ch">We source <em>off-market</em> homes, too.</h2>
    <p class="prose" style="margin:0 auto 26px;color:var(--ink-2)">Many of our completed transactions never appear on this page. Tell us the brief — bedrooms, budget, location, timeline — and we will surface matching opportunities from the private side of our book.</p>
    <!-- TODO(sam): the old site claimed "around 40% of transactions never appear here." Confirm a real figure or keep this neutral wording. -->
    <a href="${p}exclusives.html#off-market" class="btn-solid">Submit a brief ${C.icon.arrow}</a>
  </div>
</section>`;
}

/* ---------------- properties (all) ---------------- */
const properties = {
  path: 'properties.html',
  build(ctx) {
    const { site, data } = ctx;
    const all = [...data.listings, ...data.land];
    const cards = all.map((l) => C.listingCard(site, l, 0)).join('\n');
    const n = (f) => all.filter((l) => [l.status, ...(l.tags || [])].includes(f)).length;
    const chips = [
      { label: 'All', f: 'all', count: all.length },
      { label: 'Buy', f: 'sale', count: n('sale') },
      { label: 'Rent', f: 'rent', count: n('rent') },
      { label: 'Land', f: 'land', count: n('land') },
      { label: 'Under construction', f: 'under-construction', count: n('under-construction') },
      { label: 'Short stay', f: 'short-stay', count: n('short-stay') }
    ];
    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Properties' }], h1: 'The Accra<br/><em>Portfolio.</em>', sub: 'A curated selection of homes, land, and new projects — each title-verified and walked by our team before it appears on this page.' })}
${filterbar(chips, { countId: 'propCount' })}
<section class="sec bone2">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:16px;margin-bottom:28px">
      <div class="eyebrow"><span class="dot"></span>Homes, land &amp; projects</div>
      <div class="small" style="font-family:var(--mono);letter-spacing:.14em;text-transform:uppercase"><span id="propCount">${all.length}</span> listings shown</div>
    </div>
    ${grid([cards])}
    <div class="cr-empty" style="display:none;margin-top:24px"><h3>No listings match these filters</h3><p>Try a different status or area, or <a href="contact.html">tell us your brief</a>.</p></div>
  </div>
</section>
${offMarketCTA(site, 0)}`;
    return C.page(site, {
      d: 0, active: 'assets',
      title: 'Properties — Homes, Land & New Projects | Credence Real Estate',
      description: 'Browse title-verified homes for sale and rent, land plots, and new off-plan projects across Greater Accra with Credence Real Estate.',
      canonical: 'properties.html', ogImage: 'assets/royal-manhattan.png',
      body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- buy ---------------- */
const buy = {
  path: 'properties/buy.html',
  build(ctx) {
    const { site, data } = ctx;
    const items = data.listings.filter((l) => l.status === 'sale');
    const cards = items.map((l) => C.listingCard(site, l, 1)).join('\n');
    const body = `${pageHero({ d: 1, crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Properties', href: 'properties.html' }, { label: 'Buy' }], h1: 'Homes <em>for sale.</em>', sub: 'Title-verified homes for sale across Greater Accra — each one walked by our team and documented before listing.' })}
<section class="sec bone2">
  <div class="container">
    <div style="margin-bottom:28px" class="eyebrow"><span class="dot"></span>${items.length} homes for sale</div>
    ${grid([cards])}
  </div>
</section>
${offMarketCTA(site, 1)}`;
    return C.page(site, {
      d: 1, active: 'assets',
      title: 'Buy a Home in Accra | Credence Real Estate',
      description: 'Title-verified homes for sale in East Legon, Ashale Botwe and across Greater Accra. View prices, photos and references with Credence Real Estate.',
      canonical: 'properties/buy.html', ogImage: 'assets/royal-manhattan.png',
      body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- rent ---------------- */
const rent = {
  path: 'properties/rent.html',
  build(ctx) {
    const { site, data } = ctx;
    const items = data.listings.filter((l) => l.status === 'rent');
    const cards = items.map((l) => C.listingCard(site, l, 1)).join('\n');
    const inner = items.length ? grid([cards]) : `<div class="cr-empty"><h3>No rentals available right now</h3><p>New long-term rentals are added regularly. <a href="../contact.html?reason=buy">Tell us what you are looking for</a> and we will be in touch.</p></div>`;
    const body = `${pageHero({ d: 1, crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Properties', href: 'properties.html' }, { label: 'Rent' }], h1: 'Homes <em>to rent.</em>', sub: 'Long-term rentals in secure, well-connected Accra neighbourhoods. Prices are shown per month.' })}
<section class="sec bone2">
  <div class="container">
    <div style="margin-bottom:28px" class="eyebrow"><span class="dot"></span>${items.length} rental${items.length === 1 ? '' : 's'} · priced per month</div>
    ${inner}
  </div>
</section>`;
    return C.page(site, {
      d: 1, active: 'assets',
      title: 'Long-term Rentals in Accra | Credence Real Estate',
      description: 'Long-term rental homes in Accra, priced per month. Fully furnished and managed options from Credence Real Estate.',
      canonical: 'properties/rent.html', ogImage: 'assets/royal-manhattan.png',
      body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- land ---------------- */
const land = {
  path: 'properties/land.html',
  build(ctx) {
    const { site, data } = ctx;
    const plots = data.land;
    const cards = plots.map((l) => `<article class="lcard">
      <span class="ltag">Plot · ${C.esc(l.location.split('·')[0].trim())}</span>
      <h3>${C.esc(l.title)}</h3>
      <div class="larea">${C.esc(l.plotSize)} · Title-verified</div>
      <div class="lspecs">
        <div class="lrow"><span>Tenure</span><span>${C.esc(l.tenure)}</span></div>
        <div class="lrow"><span>Survey</span><span>${C.esc(l.survey)}</span></div>
        <div class="lrow"><span>Access</span><span>${C.esc(l.access)}</span></div>
      </div>
      <div class="lprice">${C.money(l)}</div>
      <div class="lactions">
        <a class="btn-ghost" href="../listings/${l.slug}.html">Details</a>
        <a class="btn-gold" href="../contact.html?reason=land">Request documents ${C.icon.arrow}</a>
      </div>
    </article>`).join('\n');
    const css = `.land-row{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.lcard{border:1px solid var(--line);border-radius:var(--r-l);padding:30px 26px;display:flex;flex-direction:column;gap:12px;background:var(--paper)}
.lcard .ltag{font-family:var(--mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-2)}
.lcard h3{font-family:var(--serif);font-weight:400;font-size:27px;line-height:1.1;margin:0;letter-spacing:-0.01em}
.lcard .larea{font-family:var(--mono);font-size:12px;letter-spacing:.04em;color:var(--ink-3)}
.lcard .lspecs{display:flex;flex-direction:column;gap:6px;margin-top:6px;font-size:14px;color:var(--ink-2)}
.lcard .lrow{display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--line)}
.lcard .lrow:last-child{border-bottom:0}
.lcard .lprice{font-family:var(--serif);font-size:30px;margin-top:8px}
.lcard .lactions{display:flex;gap:10px;margin-top:8px;flex-wrap:wrap}
.lcard .lactions a{flex:1;justify-content:center}
@media(max-width:1080px){.land-row{grid-template-columns:1fr}}`;
    const body = `${pageHero({ d: 1, crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Properties', href: 'properties.html' }, { label: 'Land' }], h1: 'Title-verified<br/><em>plots.</em>', sub: 'Every plot we present has been searched at the Lands Commission, independently surveyed, and signed off by our retained counsel — documentation available on request.' })}
<section class="sec bone2">
  <div class="container">
    <div style="margin-bottom:28px" class="eyebrow"><span class="dot"></span>${plots.length} plots · documentation on file</div>
    <div class="land-row">
      ${cards}
    </div>
  </div>
</section>
<section class="sec bone"><div class="container" style="text-align:center">
  <h2 class="t" style="margin:0 auto 14px;max-width:18ch">Buying land in Ghana? <em>Verify first.</em></h2>
  <p class="prose" style="margin:0 auto 24px;color:var(--ink-2)">An official land search at the Lands Commission is the single most important step before you pay for any plot. We run it for you.</p>
  <a href="../search-document.html" class="btn-solid">Our land search service ${C.icon.arrow}</a>
</div></section>`;
    return C.page(site, {
      d: 1, active: 'assets',
      title: 'Title-verified Land Plots in Accra | Credence Real Estate',
      description: 'Title-verified land plots in East Legon, Trasacco Valley and Tse Addo with tenure, survey and access detail. Request documents from Credence Real Estate.',
      canonical: 'properties/land.html', ogImage: 'assets/royal-manhattan.png',
      extraCss: css, body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- exclusives ---------------- */
const exclusives = {
  path: 'exclusives.html',
  build(ctx) {
    const { site, data } = ctx;
    const items = data.listings.filter((l) => (l.tags || []).includes('exclusive'));
    const cards = items.length ? grid([items.map((l) => C.listingCard(site, l, 0)).join('\n')])
      : `<div class="cr-empty"><h3>No exclusive listings right now</h3><p>Join the off-market brief below to hear first.</p></div>`;
    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Exclusives' }], h1: 'Exclusive<br/><em>listings.</em>', sub: 'A small number of homes we represent exclusively — plus access to the private, off-market side of our book.' })}
<section class="sec bone2">
  <div class="container">
    <div style="margin-bottom:28px" class="eyebrow"><span class="dot"></span>${items.length} exclusive listing${items.length === 1 ? '' : 's'}</div>
    ${cards}
  </div>
</section>
<section class="sec ink" id="off-market">
  <div class="container">
    <div class="section-head"><div class="left"><div class="eyebrow" style="color:rgba(255,255,255,.65)"><span class="dot"></span>Off-market</div><h2 class="t" style="color:#fff">Submit a <em>brief</em>.</h2></div><div class="right" style="color:rgba(255,255,255,.72)">Tell us the brief — bedrooms, budget, location, timeline — and we will surface matching opportunities that never appear on the public site.</div></div>
    <form class="cr-form" style="max-width:720px;margin-top:8px" action="https://api.web3forms.com/submit" method="POST" data-web3>
      <input type="hidden" name="access_key" value="${site.web3forms.contactKey}"/>
      <input type="hidden" name="subject" value="Off-market brief — Credence Real Estate"/>
      <input type="hidden" name="from_name" value="Credence Website"/>
      <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
      <div class="row-2"><div><label>Full name</label><input name="name" required/></div><div><label>Email</label><input type="email" name="email" required/></div></div>
      <div class="row-2"><div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div><div><label>Location / area</label><input name="location"/></div></div>
      <div class="row-2"><div><label>Budget (USD)</label><input name="budget" placeholder="e.g. 300K–500K"/></div><div><label>Bedrooms</label><input name="bedrooms"/></div></div>
      <div><label>Your brief</label><textarea name="message" placeholder="Intent (own or invest), timeline, must-haves…"></textarea></div>
      <button type="submit" class="submit">Submit brief ${C.icon.arrow}</button>
      <div class="form-msg" style="display:none"></div>
    </form>
  </div>
</section>`;
    return C.page(site, {
      d: 0, active: 'assets',
      title: 'Exclusive & Off-market Listings | Credence Real Estate',
      description: 'Homes Credence represents exclusively, plus access to our off-market book. Submit a brief and we will match you privately.',
      canonical: 'exclusives.html', ogImage: 'assets/salem-plus-home.JPG',
      body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- new projects ---------------- */
const newProjects = {
  path: 'new-projects.html',
  build(ctx) {
    const { site, data } = ctx;
    const items = data.listings.filter((l) => l.status === 'off-plan' || (l.tags || []).includes('under-construction'));
    const blocks = items.map((l) => {
      const hasPhoto = l.photos && l.photos.length;
      const media = hasPhoto ? `<img src="${l.photos[0]}" alt="${C.esc(l.title)}" loading="lazy"/>` : C.placeholder(0, l.title);
      return `<article class="np">
      <div class="np-img">${media}</div>
      <div class="np-body">
        <div class="eyebrow"><span class="dot"></span>${C.esc(l.stage || 'Under construction')}</div>
        <h2 class="t">${l.title.replace(/\s(\w+)$/, ' <em>$1</em>')}</h2>
        <div class="np-meta">
          <span><b>Stage</b> ${C.esc(l.stage || 'Under construction')}</span>
          <span><b>Delivery</b> ${C.esc(l.delivery || 'TBC')}</span>
          <span><b>From</b> ${C.money(l).replace(/<[^>]+>/g, '')}</span>
          <span><b>Beds</b> ${C.esc(l.beds)}</span>
        </div>
        <p>${C.esc(l.summary)}</p>
        <div style="display:flex;gap:10px;flex-wrap:wrap"><a class="btn-ghost" href="listings/${l.slug}.html">View project</a><a class="btn-solid" href="#register">Register interest ${C.icon.arrow}</a></div>
      </div>
    </article>`;
    }).join('\n');
    const options = items.map((l) => `<option>${C.esc(l.title)}</option>`).join('');
    const css = `.np{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;margin-bottom:48px}
.np-img{position:relative;aspect-ratio:4/3;border-radius:var(--r-l);overflow:hidden;background:var(--emerald-2)}
.np-img img{width:100%;height:100%;object-fit:cover}
.np-body h2{margin:10px 0 14px}
.np-meta{display:flex;flex-wrap:wrap;gap:8px 22px;margin-bottom:16px;font-size:14px;color:var(--ink-2)}
.np-meta b{font-family:var(--mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-2);display:block}
.np-body p{color:var(--ink-2);line-height:1.7;margin:0 0 18px}
@media(max-width:900px){.np{grid-template-columns:1fr;gap:24px}}`;
    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'New projects' }], h1: 'New projects<br/>&amp; <em>off-plan.</em>', sub: 'Reserve early at introductory off-plan pricing, with weekly site updates through to handover.' })}
<section class="sec bone2"><div class="container">
  ${items.length ? blocks : '<div class="cr-empty"><h3>No new projects open right now</h3><p>Register your interest below to hear first.</p></div>'}
</div></section>
<section class="sec bone" id="register"><div class="container">
  <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Register interest</div><h2 class="t">Get first access to <em>off-plan</em> prices.</h2></div><div class="right">Register and we will send pricing, floor plans and site updates as the project progresses.</div></div>
  <form class="cr-form" style="max-width:720px" action="https://api.web3forms.com/submit" method="POST" data-web3>
    <input type="hidden" name="access_key" value="${site.web3forms.contactKey}"/>
    <input type="hidden" name="subject" value="New project — Register interest"/>
    <input type="hidden" name="from_name" value="Credence Website"/>
    <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
    <div class="row-2"><div><label>Full name</label><input name="name" required/></div><div><label>Project</label><select name="project">${options}<option>Not sure yet</option></select></div></div>
    <div class="row-2"><div><label>Email</label><input type="email" name="email" required/></div><div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div></div>
    <div><label>Message</label><textarea name="message" placeholder="Unit preference, budget, timeline…"></textarea></div>
    <button type="submit" class="submit">Register interest ${C.icon.arrow}</button>
    <div class="form-msg" style="display:none"></div>
  </form>
</div></section>`;
    return C.page(site, {
      d: 0, active: 'assets',
      title: 'New Projects & Off-plan Homes in Accra | Credence Real Estate',
      description: 'Off-plan and under-construction developments in Accra, including Cantonments Court (delivery Q4 2026). Register interest for off-plan pricing.',
      canonical: 'new-projects.html', ogImage: 'assets/royal-manhattan.png',
      extraCss: css, body, includePopup: false
    });
  }
};

/* ---------------- short stays ---------------- */
const shortStays = {
  path: 'short-stays.html',
  build(ctx) {
    const { site, data } = ctx;
    const items = data.listings.filter((l) => l.status === 'short-stay');
    const cards = items.length ? grid([items.map((l) => C.listingCard(site, l, 0)).join('\n')])
      : `<div class="cr-empty"><h3>Short-stay homes are coming soon.</h3><p>Tell us your dates and we will match you.</p></div>`;
    // TODO(sam): no short-stay listings exist yet — add them to data/listings.json with status "short-stay".
    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Short stays' }], h1: 'Short-stay<br/><em>homes.</em>', sub: 'Furnished homes for short stays in Accra. No booking engine — tell us your dates and we match you to the right home.' })}
<section class="sec bone2"><div class="container">
  ${cards}
</div></section>
<section class="sec bone" id="request"><div class="container">
  <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Request dates</div><h2 class="t">Tell us your <em>dates.</em></h2></div><div class="right">Send your check-in and check-out dates and guest count — we will come back with available homes and nightly rates.</div></div>
  <form class="cr-form" style="max-width:720px" action="https://api.web3forms.com/submit" method="POST" data-web3>
    <input type="hidden" name="access_key" value="${site.web3forms.contactKey}"/>
    <input type="hidden" name="subject" value="Short-stay — Request dates"/>
    <input type="hidden" name="from_name" value="Credence Website"/>
    <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
    <div class="row-2"><div><label>Check-in</label><input type="date" name="checkin"/></div><div><label>Check-out</label><input type="date" name="checkout"/></div></div>
    <div class="row-2"><div><label>Guests</label><input type="number" name="guests" min="1" placeholder="2"/></div><div><label>Full name</label><input name="name" required/></div></div>
    <div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div>
    <div><label>Anything else?</label><textarea name="message" placeholder="Preferred area, budget per night…"></textarea></div>
    <button type="submit" class="submit">Request dates ${C.icon.arrow}</button>
    <div class="form-msg" style="display:none"></div>
  </form>
</div></section>`;
    return C.page(site, {
      d: 0, active: 'assets',
      title: 'Short-stay Homes in Accra | Credence Real Estate',
      description: 'Furnished short-stay homes in Accra. Short-stay listings are coming soon — send your dates and guest count and we will match you.',
      canonical: 'short-stays.html', ogImage: 'assets/royal-manhattan.png',
      body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- sell with us ---------------- */
const sell = {
  path: 'sell.html',
  build(ctx) {
    const { site } = ctx;
    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Sell with us' }], h1: 'Sell or let<br/><em>with Credence.</em>', sub: 'We represent owners selling or letting property in Accra — with honest valuations, verified documentation, and buyers from our local and diaspora network.' })}
<section class="sec bone2"><div class="container">
  <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>How it works</div><h2 class="t">A clear, documented <em>process.</em></h2></div><div class="right">We verify your title, agree a realistic price, market the property to qualified buyers and tenants, and manage viewings and negotiation through to signing.</div></div>
  <form class="cr-form" style="max-width:760px" action="https://api.web3forms.com/submit" method="POST" data-web3>
    <input type="hidden" name="access_key" value="${site.web3forms.contactKey}"/>
    <input type="hidden" name="subject" value="Sell / let with Credence — new submission"/>
    <input type="hidden" name="from_name" value="Credence Website"/>
    <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
    <div class="row-2"><div><label>Full name</label><input name="name" required/></div><div><label>Phone / WhatsApp</label><input type="tel" name="phone"/></div></div>
    <div class="row-2"><div><label>Email</label><input type="email" name="email" required/></div><div><label>Country / region</label><input name="country"/></div></div>
    <div class="row-2"><div><label>Property location</label><input name="property_location"/></div><div><label>Property type</label><select name="property_type"><option>House</option><option>Apartment</option><option>Land</option><option>Commercial</option><option>Other</option></select></div></div>
    <div class="row-2"><div><label>Bedrooms</label><input name="bedrooms"/></div><div><label>Asking price (optional)</label><input name="asking_price"/></div></div>
    <div class="row-2"><div><label>Sell or let?</label><select name="intent"><option>Sell</option><option>Let (rent out)</option><option>Not sure</option></select></div><div><label>Photos link (optional)</label><input name="photos_link" placeholder="Google Drive / Dropbox link"/></div></div>
    <div><label>Message</label><textarea name="message" placeholder="Tell us about the property…"></textarea></div>
    <button type="submit" class="submit">Submit property ${C.icon.arrow}</button>
    <div class="form-msg" style="display:none"></div>
    <p class="privacy-note">By submitting you agree to our <a href="privacy.html">privacy notice</a>. We will contact you to arrange next steps.</p>
  </form>
</div></section>`;
    return C.page(site, {
      d: 0, active: 'assets',
      title: 'Sell or Let Your Property | Credence Real Estate',
      description: 'List your Accra property for sale or to let with Credence Real Estate. Honest valuations, verified documentation and a qualified buyer network.',
      canonical: 'sell.html', ogImage: 'assets/royal-manhattan.png',
      body, includePopup: false
    });
  }
};

export const assetPages = [properties, buy, rent, land, exclusives, newProjects, shortStays, sell];
