/* One-shot migration of the two legacy hand-written blog articles to the new
   shared chrome (header/footer/mobile bar), removing Unsplash requests and the
   old "Fair Housing" footer, while preserving all article content.
   Run once:  node scripts/migrate-blog.mjs */
import { readFileSync, writeFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';
import * as C from './components.mjs';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');
const site = JSON.parse(readFileSync(join(ROOT, 'data/site.json'), 'utf8'));

const FILES = ['blog-rental-experiences-tears.html', 'blog-rental-experiences-tears-5.html'];
const newHeader = C.header(site, { active: '', d: 0 });
const newFooter = C.footer(site, 0);
const newBar = C.mobilebar(site, 0);

for (const f of FILES) {
  const full = join(ROOT, f);
  let html = readFileSync(full, 'utf8');

  // 1. stylesheets: add site.css + pages.css after base.css
  if (!html.includes('assets/site.css')) {
    html = html.replace(
      /<link rel="stylesheet" href="assets\/base\.css" ?\/>/,
      '<link rel="stylesheet" href="assets/base.css" />\n<link rel="stylesheet" href="assets/site.css" />\n<link rel="stylesheet" href="assets/pages.css" />'
    );
  }

  // 2. replace header + footer blocks
  html = html.replace(/<header class="site"[\s\S]*?<\/header>/, newHeader);
  html = html.replace(/<footer class="site">[\s\S]*?<\/footer>/, newFooter + '\n' + newBar);

  // 3. swap nav.js -> site.js
  html = html.replace(/<script src="assets\/nav\.js"><\/script>/, '<script src="assets/site.js"></script>');

  // 4. remove Unsplash: CSS hero background -> brand gradient; meta/jsonld images -> brand asset
  html = html.replace(/url\('https:\/\/images\.unsplash\.com\/[^']*'\)/g, 'linear-gradient(135deg, var(--emerald), var(--emerald-2))');
  html = html.replace(/https:\/\/images\.unsplash\.com\/[^"'\s)]*/g, site.domain + '/assets/royal-manhattan.png');

  // 5. fix stale share domain
  html = html.replace(/https:\/\/credencere\.com\//g, site.domain + '/');

  // 6. Fair Housing already removed by footer swap; guard any stray text
  html = html.replace(/PRIVACY · TERMS · FAIR HOUSING/g, '');

  writeFileSync(full, html, 'utf8');
  console.log('migrated ' + f);
}
console.log('done.');
