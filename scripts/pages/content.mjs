import * as C from '../components.mjs';
import { pageHero } from './_util.mjs';

/* ---------------- INSIGHTS ---------------- */
const insights = {
  path: 'insights.html',
  build(ctx) {
    const { site, postsData } = ctx;
    const posts = postsData.posts.slice(0, 3).map((pst) => `<a class="media-card" href="${pst.file ? pst.file : 'blog/index.html'}">
      <div class="mc-top">${C.placeholder(0, 'Credence Insights')}</div>
      <div class="mc-body"><div class="mc-k">Blog</div><div class="mc-t">${C.esc(pst.title)}</div><div class="mc-d">${C.esc(pst.excerpt)}</div><div class="mc-meta"><span>${C.esc(pst.date)}</span><span>Read ${C.icon.arrow}</span></div></div>
    </a>`).join('\n');

    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Insights' }], h1: 'Insights &amp;<br/><em>the Home Show.</em>', sub: 'Market notes, the Signature Home Show, property walkthroughs, and our latest writing on buying and building in Ghana.' })}

<section class="sec bone"><div class="container">
  <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>The Signature Home Show</div><h2 class="section-title">Every home tells<br/>a story.</h2></div><div class="right">Each episode steps inside a Credence home — the owners, the brief, the build — and ends in the room they are most proud of.</div></div>
  <div style="display:grid;grid-template-columns:1.3fr 1fr;gap:40px;align-items:center">
    <a href="https://www.youtube.com/watch?v=${site.show.latestVideoId}" target="_blank" rel="noopener" style="position:relative;display:block;aspect-ratio:16/9;border-radius:var(--r-l);overflow:hidden;box-shadow:var(--shadow-3)">
      <img src="assets/royal-manhattan.png" alt="Latest Signature Home Show episode" style="width:100%;height:100%;object-fit:cover"/>
      <span style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:76px;height:76px;border-radius:50%;background:rgba(255,255,255,.92);display:grid;place-items:center"><svg width="28" height="28" viewBox="0 0 24 24" fill="#1A1815"><path d="M8 5v14l11-7z"/></svg></span>
      <span style="position:absolute;top:18px;left:18px;background:rgba(0,0,0,.55);color:#fff;backdrop-filter:blur(8px);padding:8px 14px;border-radius:999px;font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase">Latest episode</span>
    </a>
    <div>
      <p class="prose" style="color:var(--ink-2)">Experience extraordinary homes and unforgettable stories — where architecture, design, and lifestyle come together to inspire audiences across Ghana and beyond.</p>
      <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:12px"><a class="btn-solid" href="https://www.youtube.com/watch?v=${site.show.latestVideoId}" target="_blank" rel="noopener">Watch latest ${C.icon.arrow}</a><a class="btn-ghost" href="${site.show.channel}" target="_blank" rel="noopener">All episodes</a></div>
    </div>
  </div>
</div></section>

<section class="sec bone2"><div class="container">
  <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Property walkthroughs</div><h2 class="section-title">Step inside<br/>our homes.</h2></div><div class="right">Video walkthroughs of homes currently or recently on our books. Tap any card to open the full listing.</div></div>
  <div class="media-grid">
    ${['royal-manhattan', 'beverly-homes', 'salem-plus-homes', 'adjiringanor-homes'].map((s) => {
      const l = ctx.bySlug[s];
      const top = (l.photos && l.photos.length) ? `<img src="${l.photos[0]}" alt="${C.esc(l.title)}"/>` : C.placeholder(0, l.title);
      return `<a class="media-card" href="listings/${l.slug}.html"><div class="mc-top">${top}</div><div class="mc-body"><div class="mc-k">Walkthrough</div><div class="mc-t">${C.esc(l.title)}</div><div class="mc-meta"><span>${C.esc(l.location.split('·')[0].trim())}</span><span>${C.money(l).replace(/<[^>]+>/g, '')}</span></div></div></a>`;
    }).join('\n    ')}
  </div>
</div></section>

<section class="sec bone"><div class="container">
  <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>From the blog</div><h2 class="section-title">Reading room.</h2></div><div class="right"><a class="btn-ghost" href="blog/index.html">All posts ${C.icon.arrow}</a></div></div>
  <div class="media-grid">
    ${posts}
  </div>
</div></section>

<section class="sec bone2"><div class="container">
  <div class="eyebrow"><span class="dot"></span>Market notes</div>
  <h2 class="t" style="margin-top:10px">Accra market notes</h2>
  <p class="prose" style="color:var(--ink-2);margin-top:14px">Short, practical notes on the Accra market — pricing by area, what diaspora buyers should watch, and documentation trends.</p>
  <!-- TODO(sam): supply real market notes / statistics for this section. No figures are invented here. -->
  <p class="small" style="margin-top:10px">Market notes are being prepared. <a href="contact.html">Ask us a specific question</a> in the meantime.</p>
</div></section>`;

    return C.page(site, {
      d: 0, active: '',
      title: 'Insights & the Signature Home Show | Credence Real Estate',
      description: 'The Signature Home Show, property walkthroughs, Accra market notes and the Credence blog — insight on buying and building in Ghana.',
      canonical: 'insights.html', ogImage: 'assets/royal-manhattan.png',
      body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- BLOG INDEX ---------------- */
const blogIndex = {
  path: 'blog/index.html',
  build(ctx) {
    const { site, postsData } = ctx;
    const hasPosts = postsData.posts && postsData.posts.length;
    let cards;
    if (hasPosts) {
      cards = postsData.posts.map((pst) => {
        const href = pst.file ? '../' + pst.file : (pst.slug + '.html');
        return `<a class="media-card" href="${href}">
        <div class="mc-top">${C.placeholder(1, 'Credence Blog')}</div>
        <div class="mc-body"><div class="mc-k">Article</div><div class="mc-t">${C.esc(pst.title)}</div><div class="mc-d">${C.esc(pst.excerpt)}</div><div class="mc-meta"><span>${C.esc(pst.date)}</span><span>Read ${C.icon.arrow}</span></div></div>
      </a>`;
      }).join('\n');
    } else {
      // Fallback per spec: show Home Show + walkthroughs as first entries
      cards = `<a class="media-card" href="${site.show.channel}" target="_blank" rel="noopener"><div class="mc-top">${C.placeholder(1, 'Signature Home Show')}</div><div class="mc-body"><div class="mc-k">Video</div><div class="mc-t">The Signature Home Show</div><div class="mc-d">Watch our episodes on YouTube.</div></div></a>`;
      // TODO(sam): no blog posts supplied yet — add real posts to data/posts.json.
    }
    const body = `${pageHero({ d: 1, crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Insights', href: 'insights.html' }, { label: 'Blog' }], h1: 'The Credence<br/><em>blog.</em>', sub: 'Perspectives on Ghana real estate, property management and diaspora investment.' })}
<section class="sec bone2"><div class="container">
  <div class="media-grid">
    ${cards}
  </div>
  ${hasPosts ? '' : '<p class="small" style="margin-top:18px">New articles are on the way.</p>'}
</div></section>`;
    return C.page(site, {
      d: 1, active: '',
      title: 'Blog — Ghana Real Estate & Property Insight | Credence Real Estate',
      description: 'The Credence blog: practical writing on buying, building, renting and managing property in Ghana for local and diaspora clients.',
      canonical: 'blog/index.html', ogImage: 'assets/royal-manhattan.png',
      body, includePopup: true, popupCfg: ctx.popupCfg
    });
  }
};

/* ---------------- BLOG POST TEMPLATE (not linked; for authoring) ---------------- */
const blogTemplate = {
  path: 'blog/_template.html',
  build(ctx) {
    const { site } = ctx;
    const css = `.post{max-width:760px;margin:0 auto}
.post .meta{font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--ink-3);margin-bottom:14px}
.post h1{font-family:var(--serif);font-weight:400;font-size:clamp(34px,5vw,60px);line-height:1.04;letter-spacing:-0.02em;margin:0 0 20px}
.post .hero-img{width:100%;border-radius:var(--r-l);margin:0 0 28px;aspect-ratio:16/9;object-fit:cover}
.post .body p{font-size:17px;line-height:1.8;color:var(--ink-2);margin:0 0 20px}
.post .share{display:flex;gap:10px;margin:28px 0;padding-top:20px;border-top:1px solid var(--line)}
.post .cta{background:var(--ink);color:#fff;border-radius:var(--r-l);padding:32px;text-align:center;margin-top:36px}
.post .cta h3{font-family:var(--serif);font-weight:400;font-size:28px;margin:0 0 14px;color:#fff}`;
    const body = `<!-- TEMPLATE — copy this file to blog/<slug>.html, fill the {{TOKENS}}, and add the post to data/posts.json. Do not publish with tokens unfilled. -->
${pageHero({ d: 1, crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Blog', href: 'blog/index.html' }, { label: '{{TITLE}}' }], h1: '{{TITLE}}' })}
<section class="sec bone"><div class="container"><article class="post">
  <div class="meta">{{DATE}} · By {{AUTHOR_NAME}}</div>
  <img class="hero-img" src="../{{HERO_IMAGE}}" alt="{{TITLE}}"/>
  <div class="body">
    <p>{{BODY_PARAGRAPH_1}}</p>
    <p>{{BODY_PARAGRAPH_2}}</p>
  </div>
  <div class="share">
    <a class="btn-ghost" href="https://wa.me/${site.whatsapp.number}" target="_blank" rel="noopener">Share on WhatsApp</a>
    <a class="btn-ghost" href="https://www.facebook.com/sharer/sharer.php?u=${site.domain}/blog/{{SLUG}}.html" target="_blank" rel="noopener">Share on Facebook</a>
  </div>
  <div class="cta"><h3>Thinking about a move?</h3><a class="btn-gold" href="../contact.html">Talk to us ${C.icon.arrow}</a></div>
</article></div></section>`;
    return C.page(site, {
      d: 1, active: '',
      title: '{{TITLE}} | Credence Real Estate',
      description: '{{EXCERPT}}',
      canonical: 'blog/{{SLUG}}.html',
      extraCss: css, body, includePopup: false
    });
  }
};

/* ---------------- SEARCH DOCUMENT ---------------- */
const searchDoc = {
  path: 'search-document.html',
  build(ctx) {
    const { site } = ctx;
    const steps = [
      ['01', 'Submit', 'Send us the plot location and your site plan or allocation papers through the form below.'],
      ['02', 'Pay', 'We confirm the fee and send Mobile Money or bank transfer instructions by WhatsApp or email.'],
      ['03', 'We search', 'Our team runs the official search at the Lands Commission and verifies survey and encumbrances.'],
      ['04', 'You get the report', 'You receive a clear written report with our findings and recommendation.']
    ];
    const stepHtml = steps.map((s) => `<div class="dstep"><div class="n">${s[0]}</div><div class="t">${s[1]}</div><div class="d">${s[2]}</div></div>`).join('');
    const css = `.steps-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;border-top:1px solid var(--line);margin-top:8px}
.dstep{padding:28px 22px 24px;border-bottom:1px solid var(--line)}.dstep:not(:last-child){border-right:1px solid var(--line)}
.dstep .n{font-family:var(--mono);font-size:11px;letter-spacing:.18em;color:var(--gold-2)}
.dstep .t{font-family:var(--serif);font-size:24px;margin:10px 0 6px;letter-spacing:-0.01em}
.dstep .d{font-size:14px;color:var(--ink-3);line-height:1.6}
.covers{display:grid;grid-template-columns:repeat(2,1fr);gap:12px 28px;margin-top:8px}
.covers .c{display:flex;gap:10px;font-size:15px;color:var(--ink-2);align-items:flex-start}
.covers .c .tick{width:18px;height:18px;border-radius:50%;background:var(--emerald);color:#fff;display:grid;place-items:center;flex:none;margin-top:2px}
.priceline{font-family:var(--serif);font-size:40px;letter-spacing:-0.01em}
@media(max-width:1080px){.steps-grid{grid-template-columns:1fr 1fr}.dstep:nth-child(2){border-right:0}}
@media(max-width:640px){.steps-grid{grid-template-columns:1fr}.dstep{border-right:0!important}.covers{grid-template-columns:1fr}}`;
    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Search Document' }], h1: 'Official land<br/><em>document search.</em>', sub: 'An official land search at the Lands Commission confirms who really owns a plot, whether the title is clean, and whether anyone else has a claim on it. For any buyer in Ghana, it is the step to take before you pay for land.' })}

<section class="sec bone"><div class="container">
  <div class="eyebrow"><span class="dot"></span>How it works</div>
  <h2 class="t" style="margin:10px 0 0">Four steps, one <em>turnaround.</em></h2>
  <div class="steps-grid">${stepHtml}</div>
  <p class="small" style="margin-top:16px">Typical turnaround: <strong>___ working days</strong>. <!-- TODO(sam): set the real turnaround time in working days (one consistent figure). --></p>
</div></section>

<section class="sec bone2"><div class="container">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px">
    <div>
      <div class="eyebrow"><span class="dot"></span>What the search covers</div>
      <h2 class="t" style="margin:10px 0 18px">We check the <em>whole chain.</em></h2>
      <div class="covers">
        <div class="c"><span class="tick">${C.icon.check}</span>Title search at the Lands Commission</div>
        <div class="c"><span class="tick">${C.icon.check}</span>Survey &amp; boundary verification</div>
        <div class="c"><span class="tick">${C.icon.check}</span>Encumbrances &amp; competing claims</div>
        <div class="c"><span class="tick">${C.icon.check}</span>Chief / family verification where relevant</div>
      </div>
    </div>
    <div>
      <div class="eyebrow"><span class="dot"></span>Price &amp; what to provide</div>
      <h2 class="t" style="margin:10px 0 12px">From GHS ___</h2>
      <!-- TODO(sam): set the real "from" price in GHS. No amount is invented. -->
      <p class="prose" style="color:var(--ink-2);margin-bottom:12px">You will need to provide: the <strong>site plan</strong>, the <strong>indenture or allocation papers</strong>, and the <strong>plot location</strong>.</p>
      <p class="small"><strong>Payment:</strong> there is no online payment here. Once we review your request, we send Mobile Money or bank-transfer instructions by WhatsApp or email.</p>
    </div>
  </div>
</div></section>

<section class="sec bone" id="request"><div class="container">
  <div class="section-head"><div class="left"><div class="eyebrow"><span class="dot"></span>Request a search</div><h2 class="t">Start your <em>land search.</em></h2></div><div class="right">Complete the form and we will confirm the fee and next steps. Your information is handled per our privacy notice.</div></div>
  <form class="cr-form" style="max-width:760px" action="https://api.web3forms.com/submit" method="POST" data-web3>
    <input type="hidden" name="access_key" value="${site.web3forms.contactKey}"/>
    <input type="hidden" name="subject" value="Land document search request"/>
    <input type="hidden" name="from_name" value="Credence Website"/>
    <input type="checkbox" name="botcheck" style="display:none" tabindex="-1" autocomplete="off"/>
    <div class="row-2"><div><label>Full name</label><input name="name" required/></div><div><label>Email</label><input type="email" name="email" required/></div></div>
    <div class="row-2"><div><label>Phone / WhatsApp</label><input type="tel" name="phone" required/></div><div><label>Country / region</label><input name="country"/></div></div>
    <div class="row-2"><div><label>Plot location</label><input name="plot_location" required/></div><div><label>Search type</label><select name="search_type"><option>Official search</option><option>Search with site visit</option></select></div></div>
    <div><label>Message</label><textarea name="message" placeholder="Anything we should know about the plot…"></textarea></div>
    <button type="submit" class="submit">Request land search ${C.icon.arrow}</button>
    <div class="form-msg" style="display:none"></div>
    <p class="privacy-note">We will ask you to send the site plan by WhatsApp or email after you submit. Your documents are handled per our <a href="privacy.html">privacy notice</a>.</p>
  </form>
  <!-- TODO(sam): file upload omitted — Web3Forms attachments need a paid plan (unconfirmed). Clients send the site plan by WhatsApp/email instead. Confirm the plan to enable uploads. -->
</div></section>`;
    return C.page(site, {
      d: 0, active: '',
      title: 'Land Document Search at the Lands Commission | Credence Real Estate',
      description: 'Official land document search in Ghana: we verify title, survey and encumbrances at the Lands Commission and send you a clear written report.',
      canonical: 'search-document.html', ogImage: 'assets/royal-manhattan.png',
      extraCss: css, body, includePopup: false
    });
  }
};

/* ---------------- PRIVACY ---------------- */
const privacy = {
  path: 'privacy.html',
  build(ctx) {
    const { site } = ctx;
    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Privacy' }], h1: 'Privacy<br/><em>notice.</em>', sub: 'How we collect, use and protect the information you share through this website.' })}
<section class="sec bone"><div class="container"><div class="prose">
  <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">What we collect</h3>
  <p>When you use a form on this site we collect the details you provide: your name, email address, phone or WhatsApp number, country, budget and timeline (where asked), your message, and any property or land details you send us. We do not currently collect uploaded files through the website — where documents are needed, we ask you to send them by WhatsApp or email.</p>
  <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">How your information is handled</h3>
  <p>Form submissions are delivered to us by email through <strong>Web3Forms</strong>, a third-party form service, which processes the submission in order to deliver it. We use your information only to respond to your enquiry and to provide the services you ask about.</p>
  <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">How long we keep it</h3>
  <p>We keep enquiry information for as long as needed to serve you and to meet our record-keeping obligations. <!-- TODO(sam): confirm the retention period. --> Retention period: to be confirmed.</p>
  <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Your choices</h3>
  <p>You can ask us to show you what we hold, correct it, or delete it. To do so, contact us at <a href="mailto:${site.email}">${C.esc(site.email)}</a> or on WhatsApp at ${C.esc(site.whatsapp.display)}.</p>
  <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Data controller</h3>
  <p>${C.esc(site.legalName)}, a subsidiary of ${C.esc(site.parent)}. <!-- TODO(sam): confirm the data-controller legal entity, registered address and a contact for data requests. --></p>
  <p class="small">This notice is provided in good faith and is not legal advice. <!-- TODO(sam): have this reviewed by counsel. --></p>
</div></div></section>`;
    return C.page(site, {
      d: 0, active: '',
      title: 'Privacy Notice | Credence Real Estate',
      description: 'How Credence Real Estate collects, uses and protects information submitted through this website, and how to request deletion.',
      canonical: 'privacy.html', body, includePopup: false
    });
  }
};

/* ---------------- TERMS ---------------- */
const terms = {
  path: 'terms.html',
  build(ctx) {
    const { site } = ctx;
    const body = `${pageHero({ crumbTrail: [{ label: 'Credence', href: 'index.html' }, { label: 'Terms' }], h1: 'Website<br/><em>terms.</em>', sub: 'The terms on which you use this website.' })}
<section class="sec bone"><div class="container"><div class="prose">
  <p>By using this website you accept these terms. Please read them alongside our <a href="privacy.html">privacy notice</a>.</p>
  <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Listings &amp; prices</h3>
  <p>All listings are subject to availability and to verification. Prices, specifications and availability can change without notice, and photographs and descriptions are for guidance only.</p>
  <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">No binding offer</h3>
  <p>Nothing on this website constitutes a binding offer, a valuation, or professional, legal or financial advice. Any transaction is subject to a separate written agreement and to satisfactory due diligence.</p>
  <h3 style="font-family:var(--serif);font-weight:400;font-size:26px">Your use of the site</h3>
  <p>You agree to use the site lawfully and not to misuse our forms or content. Links to third-party sites are provided for convenience and are outside our control.</p>
  <p class="small">These terms are provided in good faith and are not legal advice. <!-- TODO(sam): have these terms reviewed by counsel, and add governing law / company details. --></p>
</div></div></section>`;
    return C.page(site, {
      d: 0, active: '',
      title: 'Website Terms | Credence Real Estate',
      description: 'Website terms for Credence Real Estate: listings are subject to availability and verification, and nothing on the site is a binding offer.',
      canonical: 'terms.html', body, includePopup: false
    });
  }
};

/* ---------------- 404 ---------------- */
const notFound = {
  path: '404.html',
  build(ctx) {
    const { site } = ctx;
    const css = `.nf{min-height:72vh;display:flex;align-items:center;justify-content:center;text-align:center;background:#0F0E0C;color:#fff;padding:160px 0 90px}
.nf h1{font-family:var(--serif);font-weight:400;font-size:clamp(60px,12vw,140px);line-height:.9;margin:0 0 10px}
.nf h1 em{font-style:italic;color:var(--gold)}
.nf p{color:rgba(255,255,255,.78);font-size:18px;margin:0 auto 30px;max-width:46ch}
.nf .actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.nf .btn-ghost{color:#fff;border-color:rgba(255,255,255,.35)}
.nf .btn-ghost:hover{background:#fff;color:var(--ink);border-color:#fff}`;
    const body = `<section class="nf"><div class="container">
  <div class="eyebrow" style="color:rgba(255,255,255,.6);justify-content:center"><span class="dot"></span>Error 404</div>
  <h1>Page <em>not found.</em></h1>
  <p>The page you were looking for has moved or no longer exists. Try one of these instead.</p>
  <div class="actions">
    <a class="btn-gold" href="properties.html">View properties</a>
    <a class="btn-ghost" href="services.html">Our services</a>
    <a class="btn-ghost" href="contact.html">Contact</a>
    <a class="btn-ghost" href="https://wa.me/${site.whatsapp.number}" target="_blank" rel="noopener">${C.icon.wa} WhatsApp</a>
  </div>
</div></section>`;
    return C.page(site, {
      d: 0, active: '',
      title: 'Page Not Found | Credence Real Estate',
      description: 'The page you were looking for could not be found. Browse our properties and services or contact Credence Real Estate.',
      canonical: '404.html', extraCss: css, body, includePopup: false
    });
  }
};

export const contentPages = [insights, blogIndex, blogTemplate, searchDoc, privacy, terms, notFound];
