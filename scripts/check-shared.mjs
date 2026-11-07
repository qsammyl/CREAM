/* Fails (exit 1) if any generated page's header or footer differs from index.html,
   after normalising depth prefixes (../) and the active-nav class. */
import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';
import { globAll } from './_glob.mjs';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');
const GENERATED = globAll(ROOT); // list of generated html files (relative)

function block(html, tag) {
  const m = html.match(new RegExp(`<${tag}\\b[\\s\\S]*?</${tag}>`, 'i'));
  return m ? m[0] : null;
}
function norm(s) {
  return s.replace(/\.\.\//g, '').replace(/\s+active"/g, '"').replace(/\s+/g, ' ').trim();
}

const idx = readFileSync(join(ROOT, 'index.html'), 'utf8');
const refHeader = norm(block(idx, 'header'));
const refFooter = norm(block(idx, 'footer'));

let fails = 0;
for (const rel of GENERATED) {
  if (rel === 'blog/_template.html') continue; // authoring template
  const html = readFileSync(join(ROOT, rel), 'utf8');
  const h = block(html, 'header'), f = block(html, 'footer');
  if (!h) { console.log('✗ ' + rel + ' — no <header>'); fails++; continue; }
  if (!f) { console.log('✗ ' + rel + ' — no <footer>'); fails++; continue; }
  if (norm(h) !== refHeader) { console.log('✗ ' + rel + ' — header differs from index.html'); fails++; }
  if (norm(f) !== refFooter) { console.log('✗ ' + rel + ' — footer differs from index.html'); fails++; }
}
if (fails) { console.log(`\nSHARED CHECK FAILED — ${fails} mismatch(es).`); process.exit(1); }
console.log(`Shared check passed — header & footer identical across ${GENERATED.length} generated pages.`);
