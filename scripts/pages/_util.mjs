import * as C from '../components.mjs';

export function grid(cards) {
  return `<div class="cr-grid">\n${cards.join('\n')}\n</div>`;
}

/* filterbar: chips [{label,f,count}], area select, count target id */
export function filterbar(chips, { area = true, target = '.cr-grid', countId = '' } = {}) {
  const chipHtml = chips.map((c, i) =>
    `<button class="chip${i === 0 ? ' on' : ''}" data-f="${c.f}" aria-pressed="${i === 0}">${C.esc(c.label)}${c.count != null ? ` <span style="font-family:var(--mono);font-size:10.5px;opacity:.6">· ${c.count}</span>` : ''}</button>`
  ).join('\n        ');
  const areaSel = area ? `<div class="areasel">
        <span class="flabel">Area</span>
        <select data-area-select aria-label="Filter by area">
          <option value="all">All areas</option>
          <option value="east-legon">East Legon</option>
          <option value="ashale-botwe">Ashale Botwe</option>
          <option value="accra">Accra &amp; other</option>
        </select>
      </div>` : '';
  return `<section class="filterbar" data-filterbar data-target="${target}" ${countId ? `data-count="#${countId}"` : ''}>
    <div class="inner">
      <span class="flabel">Filter</span>
        ${chipHtml}
      ${areaSel}
    </div>
  </section>`;
}

export function pageHero({ crumbTrail, h1, sub, d = 0 }) {
  const p = C.P(d);
  const crumbs = crumbTrail.map((c, i) => {
    const sep = i < crumbTrail.length - 1 ? '<span class="sep">·</span>' : '';
    return (c.href ? `<a href="${p}${c.href}">${C.esc(c.label)}</a>` : `<span>${C.esc(c.label)}</span>`) + sep;
  }).join('');
  return `<section class="page-hero img">
  <div class="container">
    <div class="crumbs">${crumbs}</div>
    <h1>${h1}</h1>
    ${sub ? `<p class="sub">${sub}</p>` : ''}
  </div>
</section>`;
}
