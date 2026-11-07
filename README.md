# Credence Real Estate & Asset Management — website

Hand-coded static site. Pages are **generated** from data + templates by a
zero-dependency Node build script, then committed as plain HTML. No framework,
no runtime dependencies.

## Rebuild the site

```bash
node scripts/build.mjs
```

This reads `data/*.json` and regenerates: every listing page, the homepage,
all grids (properties, buy, rent, land, exclusives, new projects, short stays),
services/about/contact, insights, the blog index + post template, privacy,
terms, 404, `sitemap.xml` and `robots.txt`. The shared header, footer, mobile
drawer, sticky bar and new-projects popup come from one place
(`scripts/components.mjs`), so they are identical on every page.

## Edit content (no HTML needed)

| Change | Edit this file |
| --- | --- |
| A listing's price, beds, photos, status, features… | `data/listings.json` |
| Phone, WhatsApp, email, social links, hours | `data/site.json` |
| The new-projects popup (or turn it off) | `data/popup.json` (`"enabled": false`) |
| Blog posts shown on the index | `data/posts.json` |

After editing, run `node scripts/build.mjs`.

## Checks (run before deploying)

```bash
node scripts/check-links.mjs     # no broken internal links, no href="#", no Drive/Unsplash links
node scripts/check-shared.mjs    # header & footer identical on every generated page
```

## New blog post

1. Copy `blog/_template.html` to `blog/<slug>.html` and fill every `{{TOKEN}}`.
2. Add the post to `data/posts.json` (real title, date, author — a person, not an email).
3. `node scripts/build.mjs`. Do **not** publish posts with tokens unfilled.

## Images & video (needs ffmpeg / cwebp — not run automatically)

These tools were **not installed** in the build environment, so the conversions
below are listed for you to run locally. Install them first (e.g. on Windows:
`winget install Gyan.FFmpeg` and the libwebp `cwebp` binary, or use
<https://squoosh.app> in the browser).

### Compress photos to WebP (~1600px) with JPEG/PNG kept as fallback

```bash
# From the assets/ folder. Produces <name>.webp alongside the original.
cwebp -q 72 -resize 1600 0 royal-manhattan.png -o royal-manhattan.webp   # 1.99 MB -> target < 250 KB
cwebp -q 72 -resize 1600 0 beverly-hills.jpeg  -o beverly-hills.webp
cwebp -q 72 -resize 1600 0 salem-plus-home.JPG -o salem-plus-home.webp
# also make an 800px variant for srcset:
cwebp -q 72 -resize 800 0  royal-manhattan.png -o royal-manhattan-800.webp
```
Then switch the listing `photos` in `data/listings.json` to the `.webp` paths
(keep a JPEG/PNG fallback only if you add `<picture>` markup).

### Convert walkthrough videos to web-friendly MP4 (H.264 / AAC) + poster

```bash
# Royal Manhattan (source is a 579 MB .MOV — this is the big win)
ffmpeg -i manhattan.MOV   -vf "scale=-2:1080" -c:v libx264 -crf 24 -preset slow -c:a aac -b:a 128k -movflags +faststart manhattan.mp4
ffmpeg -i salem-miami.MOV -vf "scale=-2:1080" -c:v libx264 -crf 24 -preset slow -c:a aac -b:a 128k -movflags +faststart salem-miami.mp4
# recompress the existing beverly clip
ffmpeg -i beverly-hills.MP4 -vf "scale=-2:1080" -c:v libx264 -crf 24 -preset slow -c:a aac -b:a 128k -movflags +faststart beverly-hills.mp4
# grab a poster frame if you want a custom one
ffmpeg -i manhattan.mp4 -ss 00:00:02 -frames:v 1 manhattan-poster.jpg
```
Then set each listing's `video` in `data/listings.json` to the `.mp4` path and
rebuild. (`assets/adjiringanor-east-legon.mp4` is already a web-ready 4.6 MB MP4.)

### Logo, favicon and apple-touch icon

`assets/credence-logo.png` is 946 KB — replace it with an SVG or a PNG < 30 KB:

```bash
cwebp -q 85 -resize 160 0 credence-logo.png -o credence-logo.webp   # or export an SVG from the source art
# Apple touch icon (180x180) and a classic favicon from the logo:
ffmpeg -i credence-logo.png -vf "scale=180:180" apple-touch-icon.png
```
`favicon.svg` is already in place. Drop `apple-touch-icon.png` at the site root
and the `<head>` will pick it up if you re-add the link in
`scripts/components.mjs` (currently it references `favicon.svg` only).

## Deploy (Hostinger)

The site is plain static files. Upload the repository contents (minus `scripts/`,
`data/` and `node_modules/` — though leaving them is harmless) to the Hostinger
`public_html` root, keeping the folder structure (`listings/`, `properties/`,
`blog/`, `assets/`). Make sure `.htaccess` is uploaded (it handles clean URLs,
the 404 page, the `blog.html` redirect and security headers).

If you deploy via git, the large raw videos are git-ignored (see `.gitignore`),
so upload the converted `.mp4` files to `assets/` separately.
