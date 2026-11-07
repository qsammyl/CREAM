/* Returns the exact list of HTML files the build generates (relative paths),
   so checks run only against generated output, not legacy files. */
import { readFileSync } from 'node:fs';
import { join } from 'node:path';
import { pages } from './pages/index.mjs';

export function generatedHtml(root) {
  const data = JSON.parse(readFileSync(join(root, 'data/listings.json'), 'utf8'));
  const listings = [...data.listings, ...data.land].map((l) => 'listings/' + l.slug + '.html');
  const pagePaths = pages.map((p) => p.path);
  return [...pagePaths, ...listings];
}

// convenience default used by check-shared
export function globAll(root) { return generatedHtml(root); }
