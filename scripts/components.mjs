/* =========================================================
   CREDENCE build — shared HTML components.
   Pure functions that return HTML strings. `depth` is 0 for
   root pages and 1 for pages in listings/ , properties/ , blog/.
   ========================================================= */

export const P = (d) => (d > 0 ? '../'.repeat(d) : '');
const esc = (s) => String(s == null ? '' : s)
  .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
export { esc };

/* ---------- SVG icons ---------- */
export const icon = {
  ig: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>',
  tt: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M14 3v11a4 4 0 1 1-4-4"/><path d="M14 3a5 5 0 0 0 5 5"/></svg>',
  yt: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="6" width="18" height="12" rx="3"/><path d="M10 9v6l5-3z" fill="currentColor"/></svg>',
  fb: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
  wa: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 21l1.65-4.92A8.5 8.5 0 1 1 7.92 19.35L3 21z"/></svg>',
  phone: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.84.57 2.8.7A2 2 0 0 1 22 16.92z"/></svg>',
  pin: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
  arrow: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
  check: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12l5 5 9-12"/></svg>',
  x: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M18 6 6 18M6 6l12 12"/></svg>'
};

/* ---------- Links map (built from site config + depth) ---------- */
export function links(site, d) {
  const p = P(d);
  return {
    home: p + 'index.html',
    about: p + 'about.html',
    services: p + 'services.html',
    properties: p + 'properties.html',
    buy: p + 'properties/buy.html',
    rent: p + 'properties/rent.html',
    land: p + 'properties/land.html',
    sell: p + 'sell.html',
    exclusives: p + 'exclusives.html',
    newProjects: p + 'new-projects.html',
    shortStays: p + 'short-stays.html',
    contact: p + 'contact.html',
    insights: p + 'insights.html',
    blog: p + 'blog/index.html',
    searchDoc: p + 'search-document.html',
    privacy: p + 'privacy.html',
    terms: p + 'terms.html',
    tel: 'tel:' + site.phone.tel,
    wa: 'https://wa.me/' + site.whatsapp.number,
    mail: 'mailto:' + site.email
  };
}

/* ---------- <head> ---------- */
export function head(site, { title, description, canonical, ogImage, d = 0, extraCss = '', jsonld = '' }) {
  const p = P(d);
  const img = ogImage ? (/^https?:/.test(ogImage) ? ogImage : site.domain + '/' + ogImage.replace(/^\//, '')) : site.domain + '/assets/royal-manhattan.png';
  const cleanPath = String(canonical || '').replace(/^\//, '').replace(/index\.html$/, '').replace(/\.html$/, '');
  const url = site.domain + '/' + cleanPath;
  return `<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>${esc(title)}</title>
<meta name="description" content="${esc(description)}"/>
<link rel="canonical" href="${esc(url)}"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="${esc(title)}"/>
<meta property="og:description" content="${esc(description)}"/>
<meta property="og:url" content="${esc(url)}"/>
<meta property="og:image" content="${esc(img)}"/>
<meta property="og:site_name" content="${esc(site.legalName)}"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="${esc(title)}"/>
<meta name="twitter:description" content="${esc(description)}"/>
<meta name="twitter:image" content="${esc(img)}"/>
<link rel="icon" href="${p}favicon.svg" type="image/svg+xml"/>
<link rel="icon" href="${p}favicon.svg" sizes="any"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="${p}assets/base.css"/>
<link rel="stylesheet" href="${p}assets/site.css"/>
<link rel="stylesheet" href="${p}assets/pages.css"/>
${jsonld ? `<script type="application/ld+json">${jsonld}</script>\n` : ''}${extraCss ? `<style>${extraCss}</style>` : ''}`;
}

/* ---------- Header (two-tier) ---------- */
export function header(site, { active = '', d = 0 } = {}) {
  const l = links(site, d);
  const act = (k) => (active === k ? ' active' : '');
  const s = site.social;
  return `<a class="skip-link" href="#main" style="position:absolute;left:-9999px">Skip to content</a>
<header class="site" id="siteHeader" data-overlay>
  <div class="util-bar">
    <div class="util-inner">
      <div class="util-left">
        <a href="${l.blog}">Blog</a>
        <a href="${l.insights}">Insights</a>
        <a href="${l.searchDoc}">Search Document</a>
      </div>
      <div class="util-right">
        <a class="util-phone" href="${l.tel}">${esc(site.phone.display)}</a>
        <a href="${l.wa}" target="_blank" rel="noopener">WhatsApp</a>
        <span class="util-social">
          <a href="${s.instagram.url}" target="_blank" rel="noopener" aria-label="Instagram">${icon.ig}</a>
          <a href="${s.tiktok.url}" target="_blank" rel="noopener" aria-label="TikTok">${icon.tt}</a>
          <a href="${s.youtube.url}" target="_blank" rel="noopener" aria-label="YouTube">${icon.yt}</a>
          <a href="${s.facebook.url}" target="_blank" rel="noopener" aria-label="Facebook">${icon.fb}</a>
        </span>
      </div>
    </div>
  </div>
  <div class="main-bar">
    <div class="row">
      <a class="brand" href="${l.home}"><span class="mark">C</span><span class="word"><span class="a">Credence</span><span class="b">Real Estate</span></span></a>
      <nav class="mainnav" aria-label="Primary">
        <div class="has-mega">
          <button class="navitem${act('assets')}" aria-expanded="false" aria-controls="mega-assets" aria-haspopup="true">Assets <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg></button>
          <div class="mega" id="mega-assets" role="menu">
            <div class="col"><h4>Properties</h4>
              <a role="menuitem" href="${l.buy}">Buy</a>
              <a role="menuitem" href="${l.rent}">Rent</a>
              <a role="menuitem" href="${l.sell}">Sell with us</a>
              <a role="menuitem" href="${l.land}">Land</a>
            </div>
            <div class="col"><h4>Exclusives</h4>
              <a role="menuitem" href="${l.exclusives}">Exclusive listings</a>
              <a role="menuitem" href="${l.newProjects}">New projects &amp; off-plan</a>
              <a role="menuitem" href="${l.exclusives}#off-market">Off-market brief</a>
            </div>
            <div class="col"><h4>Short stays</h4>
              <a role="menuitem" href="${l.shortStays}">Short-stay homes</a>
              <a role="menuitem" href="${l.shortStays}#request">Request dates</a>
              <a role="menuitem" href="${l.properties}">All properties</a>
            </div>
          </div>
        </div>
        <div class="has-drop">
          <button class="navitem${act('services')}" aria-expanded="false" aria-controls="drop-services" aria-haspopup="true">Services <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg></button>
          <div class="drop" id="drop-services" role="menu">
            <a role="menuitem" href="${l.services}#development">Development</a>
            <a role="menuitem" href="${l.services}#sales">Sales &amp; Investment</a>
            <a role="menuitem" href="${l.services}#management">Property Management</a>
            <a role="menuitem" href="${l.services}#construction">Construction &amp; Project Management</a>
            <a role="menuitem" href="${l.services}#interiors">Interiors</a>
            <a role="menuitem" href="${l.services}#land">Land Acquisition &amp; Due Diligence</a>
          </div>
        </div>
        <a class="navitem${act('about')}" href="${l.about}">About</a>
        <a class="navitem${act('contact')}" href="${l.contact}">Contact</a>
      </nav>
      <div class="tools">
        <a class="cta" href="${l.contact}">Talk to us</a>
        <button class="nav-toggle" aria-label="Menu" aria-expanded="false" aria-controls="mobileDrawer"><span></span><span></span><span></span></button>
      </div>
    </div>
  </div>
</header>
${drawer(site, d)}`;
}

/* ---------- Mobile drawer ---------- */
export function drawer(site, d = 0) {
  const l = links(site, d);
  return `<div class="drawer-scrim" id="drawerScrim"></div>
<aside class="drawer" id="mobileDrawer" aria-label="Menu">
  <div class="drawer-head">
    <a class="brand" href="${l.home}"><span class="mark">C</span> Credence</a>
    <button class="d-close" aria-label="Close menu">${icon.x}</button>
  </div>
  <div class="d-group">
    <button class="d-acc" aria-expanded="true" aria-controls="dp-assets">Assets <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg></button>
    <div class="d-panel open" id="dp-assets">
      <h5>Properties</h5>
      <a href="${l.buy}">Buy</a><a href="${l.rent}">Rent</a><a href="${l.sell}">Sell with us</a><a href="${l.land}">Land</a>
      <h5>Exclusives</h5>
      <a href="${l.exclusives}">Exclusive listings</a><a href="${l.newProjects}">New projects &amp; off-plan</a><a href="${l.exclusives}#off-market">Off-market brief</a>
      <h5>Short stays</h5>
      <a href="${l.shortStays}">Short-stay homes</a><a href="${l.shortStays}#request">Request dates</a>
    </div>
  </div>
  <div class="d-group">
    <button class="d-acc" aria-expanded="false" aria-controls="dp-services">Services <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg></button>
    <div class="d-panel" id="dp-services">
      <a href="${l.services}#development">Development</a><a href="${l.services}#sales">Sales &amp; Investment</a><a href="${l.services}#management">Property Management</a><a href="${l.services}#construction">Construction &amp; Project Management</a><a href="${l.services}#interiors">Interiors</a><a href="${l.services}#land">Land Acquisition &amp; Due Diligence</a>
    </div>
  </div>
  <a class="d-link" href="${l.about}">About</a>
  <a class="d-link" href="${l.contact}">Contact</a>
  <div class="d-util">
    <a href="${l.blog}">Blog</a>
    <a href="${l.insights}">Insights</a>
    <a href="${l.searchDoc}">Search Document</a>
  </div>
  <a class="d-cta" href="${l.contact}">Talk to us</a>
</aside>`;
}

/* ---------- Mobile sticky bar ---------- */
export function mobilebar(site, d = 0) {
  const l = links(site, d);
  return `<nav class="mobilebar" aria-label="Quick actions">
  <div class="mb-inner">
    <a href="${l.properties}">${icon.pin}Properties</a>
    <a class="wa" href="${l.wa}" target="_blank" rel="noopener">${icon.wa}WhatsApp</a>
    <a href="${l.tel}">${icon.phone}Call</a>
  </div>
</nav>`;
}

/* ---------- Footer ---------- */
export function footer(site, d = 0) {
  const l = links(site, d);
  const s = site.social;
  return `<footer class="site">
  <div class="container">
    <div class="top">
      <div class="brandblock">
        <h4>Credence<br/><em>Real Estate</em></h4>
        <p>A subsidiary of Credence Holdings Global — owning, managing, building, selling, and renting property in Accra and across Ghana. By appointment.</p>
        <div class="socials">
          <a href="${s.instagram.url}" target="_blank" rel="noopener" aria-label="Instagram">${icon.ig}</a>
          <a href="${s.tiktok.url}" target="_blank" rel="noopener" aria-label="TikTok">${icon.tt}</a>
          <a href="${s.youtube.url}" target="_blank" rel="noopener" aria-label="YouTube">${icon.yt}</a>
          <a href="${s.facebook.url}" target="_blank" rel="noopener" aria-label="Facebook">${icon.fb}</a>
          <a href="${l.wa}" target="_blank" rel="noopener" aria-label="WhatsApp">${icon.wa}</a>
        </div>
      </div>
      <div><h5>Assets</h5>
        <ul>
          <li><a href="${l.buy}">Buy</a></li>
          <li><a href="${l.rent}">Rent</a></li>
          <li><a href="${l.land}">Land</a></li>
          <li><a href="${l.sell}">Sell with us</a></li>
          <li><a href="${l.exclusives}">Exclusives</a></li>
          <li><a href="${l.newProjects}">New projects</a></li>
          <li><a href="${l.shortStays}">Short stays</a></li>
        </ul></div>
      <div><h5>Services</h5>
        <ul>
          <li><a href="${l.services}#development">Development</a></li>
          <li><a href="${l.services}#sales">Sales &amp; Investment</a></li>
          <li><a href="${l.services}#management">Property Management</a></li>
          <li><a href="${l.services}#construction">Construction</a></li>
          <li><a href="${l.services}#interiors">Interiors</a></li>
          <li><a href="${l.services}#land">Land &amp; Due Diligence</a></li>
        </ul></div>
      <div class="offices">
        <h5>Insights</h5>
        <ul style="margin-bottom:22px">
          <li><a href="${l.insights}">Insights hub</a></li>
          <li><a href="${l.blog}">Blog</a></li>
          <li><a href="${site.show.channel}" target="_blank" rel="noopener">Signature Home Show</a></li>
          <li><a href="${l.searchDoc}">Search Document</a></li>
        </ul>
        <h5>Company</h5>
        <div class="of"><b>Accra HQ</b>East Legon, Greater Accra · Ghana<br/><a href="${l.tel}">${esc(site.phone.display)}</a></div>
        <div class="of"><a href="${l.mail}">${esc(site.email)}</a><br/><a href="${l.wa}" target="_blank" rel="noopener">WhatsApp ${esc(site.whatsapp.display)}</a></div>
        <ul>
          <li><a href="${l.about}">About</a></li>
          <li><a href="${l.contact}">Contact</a></li>
          <li><a href="${l.privacy}">Privacy</a></li>
          <li><a href="${l.terms}">Terms</a></li>
        </ul>
      </div>
    </div>
    <div class="bot">
      <div>© 2026 CREDENCE REAL ESTATE &amp; ASSET MGT. · SUBSIDIARY OF CREDENCE HOLDINGS GLOBAL · REGISTERED IN GHANA 2022</div>
      <div><a href="${l.privacy}">PRIVACY</a> · <a href="${l.terms}">TERMS</a></div>
    </div>
  </div>
</footer>
<!-- TODO(sam): add company registration number and Real Estate Agency Council (REAC) licence number to the footer bottom line. -->`;
}

/* ---------- New-projects popup ---------- */
export function popup(site, popupCfg, d = 0) {
  if (!popupCfg || !popupCfg.enabled) return '';
  const p = P(d);
  const img = popupCfg.image ? `${p}${popupCfg.image.replace(/^\//, '')}` : '';
  const hasImg = !!img;
  const photo = hasImg
    ? `<div class="cr-photo" style="background-image:url('${esc(img)}')"></div>`
    : '';
  const lines = (popupCfg.lines || []).map((x) => `<p class="cr-line">${esc(x)}</p>`).join('\n        ');
  return `<div class="cr-modal-scrim" id="crPopup">
  <div class="cr-modal ${hasImg ? '' : 'no-image'}" role="dialog" aria-modal="true" aria-labelledby="crPopupTitle">
    <button class="cr-close" aria-label="Close">${icon.x}</button>
    ${photo}
    <div class="cr-body">
      <div class="cr-eyebrow">${esc(popupCfg.eyebrow)}</div>
      <h2 id="crPopupTitle">${esc(popupCfg.headline)}</h2>
        ${lines}
      <div class="cr-actions">
        <a class="btn-solid" href="${p}${esc(popupCfg.viewHref)}">View project ${icon.arrow}</a>
        <a class="btn-ghost" href="${p}${esc(popupCfg.registerHref)}">Register interest</a>
      </div>
      <p class="cr-foot">${esc(popupCfg.footnote)}</p>
    </div>
  </div>
</div>
<script>window.CR_POPUP=${JSON.stringify({ enabled: true })};</script>`;
}

/* ---------- Scripts tail ---------- */
export function scripts(site, d = 0, extra = '') {
  const p = P(d);
  return `${extra ? extra + '\n' : ''}<script src="${p}assets/site.js"></script>`;
}

/* ---------- Full page assembler ---------- */
export function page(site, opts) {
  const {
    d = 0, active = '', title, description, canonical, ogImage,
    extraCss = '', jsonld = '', bodyClass = '', body = '',
    includePopup = false, popupCfg = null, extraScripts = ''
  } = opts;
  const cls = [bodyClass].filter(Boolean).join(' ');
  return `<!doctype html>
<html lang="en">
<head>
${head(site, { title, description, canonical, ogImage, d, extraCss, jsonld })}
</head>
<body${cls ? ` class="${cls}"` : ''}>
${header(site, { active, d })}
<main id="main">
${body}
</main>
${footer(site, d)}
${mobilebar(site, d)}
${includePopup ? popup(site, popupCfg, d) : ''}
${scripts(site, d, extraScripts)}
</body>
</html>`;
}

/* ---------- Listing helpers ---------- */
export function money(l) {
  if (l.price == null || l.price === '') return 'Price on request';
  const n = Number(l.price).toLocaleString('en-US');
  let out = `${l.currency} ${n}`;
  if (l.pricePeriod === 'month') out += ' <span class="per">/mo</span>';
  else if (l.pricePeriod === 'night') out += ' <span class="per">/night</span>';
  return out;
}
export function moneyPlain(l) {
  if (l.price == null || l.price === '') return 'Price on request';
  const n = Number(l.price).toLocaleString('en-US');
  let out = `${l.currency} ${n}`;
  if (l.pricePeriod === 'month') out += ' / month';
  else if (l.pricePeriod === 'night') out += ' / night';
  return out;
}
export const STATUS_LABEL = {
  sale: 'For Sale', rent: 'For Rent', land: 'Land',
  'off-plan': 'Off-plan', 'under-construction': 'Under Construction', 'short-stay': 'Short Stay'
};
export function waHref(site, l) {
  const msg = `Hi Credence, I'm interested in ${l.title} (Ref ${l.ref}). Please share more details.`;
  return 'https://wa.me/' + site.whatsapp.number + '?text=' + encodeURIComponent(msg);
}

/* Branded placeholder (no stock photos) */
export function placeholder(d = 0, label = 'Photo coming soon') {
  const rootCls = d === 0 ? ' ph-root' : '';
  return `<div class="ph-brand${rootCls}"><span class="ph-mark"></span><span class="ph-word">Credence</span><span class="ph-sub">${esc(label)}</span></div>`;
}

/* Shared listing card for grids */
export function listingCard(site, l, d = 0) {
  const p = P(d);
  const href = p + 'listings/' + l.slug + '.html';
  const hasPhoto = l.photos && l.photos.length;
  const imgPath = hasPhoto ? p + l.photos[0].replace(/^\//, '') : '';
  const statusCls = l.status === 'sale' ? 'em' : (l.status === 'land' || l.status === 'off-plan' || l.status === 'rent' ? 'gold' : '');
  const statusLabel = STATUS_LABEL[l.status] || '';
  const img = hasPhoto
    ? `<img src="${esc(imgPath)}" alt="${esc(l.title)} — ${esc(l.location)}" loading="lazy"/>`
    : placeholder(d, l.title);
  const beds = l.beds !== '' && l.beds != null ? `<span>${esc(l.beds)} <span class="dim">bd</span></span>` : '';
  const baths = l.baths !== '' && l.baths != null ? `<span>${esc(l.baths)} <span class="dim">ba</span></span>` : '';
  const plot = l.plotSize ? `<span>${esc(l.plotSize)}</span>` : '';
  const verified = l.titleVerified ? `<span class="cr-verified">${icon.check} Title verified</span>` : '';
  const dataTags = [l.status, ...(l.tags || [])].join(' ');
  const titleHtml = l.title.replace(/\s(\w+)$/, ' <em>$1</em>');
  return `<article class="cr-card" data-tags="${esc(dataTags)}" data-area="${esc(l.area_key || '')}">
  <div class="cr-img">
    ${statusLabel ? `<span class="cr-tag ${statusCls}">${esc(statusLabel)}</span>` : ''}
    ${img}
  </div>
  <div class="cr-cbody">
    <div class="cr-price">${money(l)}</div>
    <div class="cr-ttl">${titleHtml}</div>
    <div class="cr-loc">${icon.pin}${esc(l.location)}</div>
    <div class="cr-ref">REF · ${esc(l.ref)}</div>
    <div class="cr-stats">${beds}${baths}${plot}${verified}</div>
  </div>
  <a class="cr-cover" href="${esc(href)}" aria-label="${esc(l.title)}">${esc(l.title)}</a>
</article>`;
}
