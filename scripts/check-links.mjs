/* Link check across generated pages:
   - no broken internal links (file must exist)
   - no href="#"
   - no links to drive.google.com or unsplash
   Exits 1 on any failure. */
import { readFileSync, existsSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join, resolve, normalize } from 'node:path';
import { generatedHtml } from './_glob.mjs';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');
const files = generatedHtml(ROOT);
const FORBIDDEN = [/drive\.google\.com/i, /unsplash\.com/i];

let errors = [];
let checked = 0;

for (const rel of files) {
  if (rel === 'blog/_template.html') continue; // authoring template with {{TOKENS}}
  const full = join(ROOT, rel);
  if (!existsSync(full)) { errors.push(`${rel}: generated file missing`); continue; }
  const html = readFileSync(full, 'utf8');
  const dir = dirname(full);
  const attrs = [...html.matchAll(/(?:href|src)\s*=\s*"([^"]*)"/gi)].map((m) => m[1]);
  for (const raw of attrs) {
    checked++;
    const href = raw.trim();
    if (href === '#') { errors.push(`${rel}: empty anchor href="#"`); continue; }
    for (const rx of FORBIDDEN) if (rx.test(href)) errors.push(`${rel}: forbidden link → ${href}`);
    if (/^(https?:|mailto:|tel:|data:|javascript:)/i.test(href)) continue; // external/intent
    if (href.startsWith('#')) continue; // same-page anchor
    const clean = href.split('#')[0].split('?')[0];
    if (!clean) continue;
    const target = normalize(resolve(dir, clean));
    if (!existsSync(target)) errors.push(`${rel}: broken internal link → ${href}`);
  }
}

if (errors.length) {
  console.log('LINK CHECK FAILED:\n' + errors.map((e) => '  ✗ ' + e).join('\n'));
  console.log(`\n${errors.length} problem(s) across ${files.length} pages.`);
  process.exit(1);
}
console.log(`Link check passed — ${checked} links across ${files.length} pages, no broken/forbidden links.`);
