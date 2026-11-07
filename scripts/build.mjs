/* =========================================================
   CREDENCE static-site build. Zero dependencies.
   Run:  node scripts/build.mjs
   Reads data/*.json, emits plain HTML (committed to the repo).
   ========================================================= */
import { readFileSync, writeFileSync, mkdirSync, existsSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';
import * as C from './components.mjs';
import { pages } from './pages/index.mjs';

const __dirname = dirname(fileURLToPath(import.meta.url));
const ROOT = join(__dirname, '..');
const read = (f) => JSON.parse(readFileSync(join(ROOT, f), 'utf8'));

const site = read('data/site.json');
const data = read('data/listings.json');
const popupCfg = read('data/popup.json');
const postsData = read('data/posts.json');
const allListings = [...data.listings, ...data.land];
const bySlug = Object.fromEntries(allListings.map((l) => [l.slug, l]));

const ctx = { site, data, popupCfg, postsData, allListings, bySlug, C };

let written = 0;
function out(rel, html) {
  const full = join(ROOT, rel);
  mkdirSync(dirname(full), { recursive: true });
  writeFileSync(full, html, 'utf8');
  written++;
  console.log('  ✓ ' + rel);
}
ctx.out = out;

/* ---------- Listing detail page ---------- */
function mapEmbed(l) {
  const q = encodeURIComponent((l.location || '') + ', Accra, Ghana');
  return `<div class="map-embed"><iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="${C.esc(l.title)} — ${C.esc(l.location)}" src="https://maps.google.com/maps?q=${q}&t=&ie=UTF8&output=embed"></iframe></div>`;
}
function renderListing(l) {
  const d = 1;
  const p = C.P(d);
  const isLand = l.status === 'land';
  const statusLabel = C.STATUS_LABEL[l.status] || '';
  const statusCls = l.status === 'sale' ? 'sale' : (l.status === 'rent' ? 'rent' : (l.status === 'off-plan' ? 'offplan' : (l.status === 'land' ? 'land' : 'offplan')));
  const hasPhoto = l.photos && l.photos.length;
  const heroBg = hasPhoto ? `${p}${l.photos[0].replace(/^\//, '')}` : '';

  // gallery
  let gallery = '';
  if (hasPhoto) {
    gallery = `<div class="gallery">` + l.photos.map((ph, i) =>
      `<figure class="${i === 0 ? 'g0' : ''}"><img src="${p}${ph.replace(/^\//, '')}" alt="${C.esc(l.title)} — photo ${i + 1}" loading="lazy" data-full="${p}${ph.replace(/^\//, '')}"/></figure>`
    ).join('') + `</div>`;
  } else {
    gallery = `<div class="gallery"><figure class="g0" style="cursor:default">${C.placeholder(d, l.title)}</figure></div>
    <!-- ${C.esc(l.photosTodo || 'TODO(sam): supply real photos.')} -->`;
  }

  // video
  let video = '';
  if (l.video) {
    const poster = hasPhoto ? ` poster="${p}${l.photos[0].replace(/^\//, '')}"` : '';
    video = `<div class="vid-wrap"><video controls preload="none"${poster}><source src="${p}${l.video.replace(/^\//, '')}" type="video/mp4"/>Your browser does not support the video tag.</video></div>
    ${l.videoSource ? `<!-- TODO(sam): convert ${l.videoSource} to ${l.video} (H.264/AAC MP4). See README. -->` : ''}`;
  }

  // specs
  const specRows = [];
  if (l.beds !== '' && l.beds != null) specRows.push(['Bedrooms', l.beds]);
  if (l.baths !== '' && l.baths != null) specRows.push(['Bathrooms', l.baths]);
  if (l.area) specRows.push(['Floor area', l.area]);
  if (l.plotSize) specRows.push(['Plot size', l.plotSize]);
  if (l.tenure) specRows.push(['Tenure', l.tenure]);
  if (l.survey) specRows.push(['Survey', l.survey]);
  if (l.access) specRows.push(['Access', l.access]);
  if (l.delivery) specRows.push(['Expected delivery', l.delivery]);
  specRows.push(['Location', l.location]);
  specRows.push(['Reference', l.ref]);

  const features = (l.features || []).map((f) => `<div class="feature-item"><span class="fdot"></span>${C.esc(f)}</div>`).join('');
  const docs = (l.documents || []).map((x) => `<li>${C.icon.check} ${C.esc(x)}</li>`).join('');

  const verifiedBadge = l.titleVerified
    ? `<span class="verified">${C.icon.check} Title verified</span>` : '';
  const verifiedSection = l.titleVerified && l.verifiedSummary
    ? `<div class="prop-section"><h3>Title verified</h3><p>${C.esc(l.verifiedSummary)}</p></div>` : '';

  const jsonld = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'RealEstateListing',
    name: l.title,
    url: site.domain + '/listings/' + l.slug + '.html',
    description: l.summary || '',
    ...(hasPhoto ? { image: site.domain + '/' + l.photos[0].replace(/^\//, '') } : {}),
    ...(l.price ? { offers: { '@type': 'Offer', price: l.price, priceCurrency: l.currency, availability: 'https://schema.org/InStock' } } : {}),
    address: { '@type': 'PostalAddress', addressLocality: l.location, addressRegion: 'Greater Accra', addressCountry: 'GH' },
    ...(l.beds && !isLand ? { numberOfBedrooms: l.beds } : {})
  });

  const body = `<section class="prop-hero">
  ${hasPhoto ? `<div class="bg" style="background-image:url('${heroBg}')"></div>` : `<div class="bg">${C.placeholder(d, l.title)}</div>`}
  <div class="scrim"></div>
  <div class="content container">
    <div class="crumbs"><a href="${p}index.html">Credence</a><span class="sep">/</span><a href="${p}properties.html">Properties</a><span class="sep">/</span>${C.esc(l.title)}</div>
    <div class="badge-row">
      <span class="status ${statusCls}">${C.esc(statusLabel)}</span>
      ${verifiedBadge}
      <span class="ref-pill">REF · ${C.esc(l.ref)}</span>
    </div>
    <h1>${l.title.replace(/\s(\w+)$/, ' <em>$1</em>')}</h1>
    <div class="addr">${C.icon.pin} ${C.esc(l.location)} · Ghana</div>
  </div>
</section>

<section class="prop-main">
  <div class="container">
    <a class="back-link" href="${p}properties.html"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M19 12H5M11 6l-6 6 6 6"/></svg> Back to Properties</a>
    <div class="prop-layout">
      <div>
        <div class="prop-price">${C.money(l)}</div>
        <div class="prop-price-sub">${C.esc(l.priceNote || '')}</div>
        ${gallery}
        ${video}
        <div class="prop-section">
          <h3>About this property</h3>
          ${l.summary ? `<p>${C.esc(l.summary)}</p>` : ''}
          ${l.summary2 ? `<p>${C.esc(l.summary2)}</p>` : ''}
          ${l.availability ? `<p><strong>Availability:</strong> ${C.esc(l.availability)}.</p>` : ''}
        </div>
        ${features ? `<div class="prop-section"><h3>${isLand ? 'Plot details' : 'Key features'}</h3><div class="features-grid">${features}</div></div>` : ''}
        ${verifiedSection}
        ${docs ? `<div class="prop-section"><h3>Documents available on request</h3><ul class="doc-list">${docs}</ul></div>` : ''}
        <div class="prop-section"><h3>Location</h3><p>Approximate location shown by area, not exact address. ${mapEmbed(l)}</p></div>
      </div>

      <aside>
        <div class="inquiry-card">
          <div class="agent">
            <div class="av"></div>
            <div><div class="name">Credence RE Advisory</div><div class="role">Licensed Real Estate Advisors</div></div>
          </div>
          <h3>${isLand ? 'Request documents' : 'Book a viewing'}</h3>
          <div class="ref-tag">REF · ${C.esc(l.ref)} · ${C.esc(l.location.split('·')[0].trim())}</div>
          <div class="inquiry-actions">
            <a href="${C.waHref(site, l)}" target="_blank" rel="noopener" class="wa">${C.icon.wa} WhatsApp</a>
            <a href="${p ? 'tel:' + site.phone.tel : 'tel:' + site.phone.tel}">${C.icon.phone} Call</a>
          </div>
          <form action="https://api.web3forms.com/submit" method="POST" data-web3>
            <input type="hidden" name="access_key" value="${site.web3forms.listingKey}"/>
            <input type="hidden" name="subject" value="Property Enquiry — ${C.esc(l.title)} (${C.esc(l.ref)})"/>
            <input type="hidden" name="from_name" value="Credence RE Website"/>
            <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
            <input type="text" name="name" placeholder="Full name" required/>
            <input type="email" name="email" placeholder="Email address" required/>
            <input type="tel" name="phone" placeholder="Phone / WhatsApp"/>
            <textarea name="message" placeholder="${isLand ? 'Which documents would you like? Preferred contact time…' : 'Your message or preferred viewing time…'}"></textarea>
            <button type="submit" class="submit">${isLand ? 'Request documents' : 'Submit enquiry'}</button>
            <div class="form-msg" style="display:none"></div>
          </form>
          <p class="small-note">Or call us on <a href="tel:${site.phone.tel}">${C.esc(site.phone.display)}</a> · By appointment only</p>
        </div>
      </aside>
    </div>
  </div>
</section>`;

  return C.page(site, {
    d, active: 'assets',
    title: `${l.title} — ${C.esc(l.location.split('·')[0].trim())} | Credence Real Estate`,
    description: (l.summary || `${l.title} in ${l.location}. ${C.moneyPlain(l)}. Enquire with Credence Real Estate.`).slice(0, 157),
    canonical: `listings/${l.slug}.html`,
    ogImage: hasPhoto ? l.photos[0] : 'assets/royal-manhattan.png',
    jsonld,
    body,
    includePopup: true, popupCfg
  });
}

/* ---------- build everything ---------- */
console.log('Building Credence site…');

// Listing detail pages
for (const l of allListings) out('listings/' + l.slug + '.html', renderListing(l));

// Content pages (from scripts/pages/*)
for (const def of pages) {
  const result = def.build(ctx);
  out(def.path, result);
}

/* ---------- sitemap.xml ---------- */
const urls = [
  'index.html', 'about.html', 'services.html', 'properties.html', 'contact.html',
  'properties/buy.html', 'properties/rent.html', 'properties/land.html',
  'sell.html', 'exclusives.html', 'new-projects.html', 'short-stays.html',
  'search-document.html', 'insights.html', 'blog/index.html', 'privacy.html', 'terms.html',
  ...allListings.map((l) => 'listings/' + l.slug + '.html'),
  ...postsData.posts.filter((p) => p.file).map((p) => p.file)
];
const today = new Date().toISOString().slice(0, 10);
const clean = (u) => u.replace(/index\.html$/, '').replace(/\.html$/, '');
const sitemap = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${urls.map((u) => `  <url><loc>${site.domain}/${clean(u)}</loc><lastmod>${today}</lastmod></url>`).join('\n')}
</urlset>
`;
out('sitemap.xml', sitemap);

/* ---------- robots.txt ---------- */
const robots = `# Cloudflare content-signal preferences (preserved)
# NOTE: AI content signals
User-agent: *
Allow: /

Sitemap: ${site.domain}/sitemap.xml
`;
out('robots.txt', robots);

console.log(`\nDone. ${written} files written.`);
