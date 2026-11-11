# CREAM — Deployment & cut-over runbook

How to put the **dynamic Laravel app** (`cream-app/`) live on Hostinger, and how
to switch over from the current static site. Written for the site owner doing the
deploy by hand (SSH / Hostinger File Manager).

> **Nothing here runs automatically.** Claude Code builds the release package
> locally; every server step below is run by you. The live server is never
> touched by the tooling.

---

## 0. The production layout

Hostinger's document root (`public_html`) can't be moved, so the app sits *beside*
it and only the web root's contents go inside `public_html`:

```
/home/<user>/
  ├── cream-app/        ← the Laravel app (this repo's cream-app/), NOT web-reachable
  │     ├── app/ bootstrap/ config/ database/ resources/ routes/ storage/ vendor/
  │     ├── artisan  composer.json  .env   ← .env lives here, outside the web root
  │     └── ...
  └── public_html/      ← the document root (web root)
        ├── index.php    ← path-adjusted front controller (points up into ../cream-app)
        ├── .htaccess
        ├── assets/ build/ favicon.svg robots.txt
        └── uploads/     ← UPLOADS_ROOT points here; images are written here
```

Because `.env`, `vendor`, `storage` and all app code live outside `public_html`,
they are never web-reachable by construction.

---

## 1. One-time server prep (hPanel)

1. **PHP 8.4** — set the site's PHP version to **8.4** (matches local; the app is
   pinned to 8.4). Confirm the CLI binary too: `php -v` over SSH should report 8.4.
   If not, note the versioned binary path (e.g. `/usr/bin/php8.4`) — use it wherever
   this runbook says `php`.
2. **Database** — create a MySQL/MariaDB database and a dedicated user, grant it
   all privileges on that database. Keep the name / user / password for the `.env`.
   (Confirm the engine version once: `SELECT VERSION();` — 10.11 locally is a safe
   match for Hostinger's MariaDB.)
3. **SSH** — enable SSH access (Hostinger Business). You'll need it for Composer,
   migrations and the cron.
4. **Composer** — confirm `composer --version` works over SSH. If it doesn't,
   build the package with `-IncludeVendor` (see §2) so you can skip Composer.

---

## 2. Build the release package (local)

From `cream-app/` on your Windows machine (Herd on PATH):

```powershell
$env:Path = "$env:USERPROFILE\.config\herd\bin;" + $env:Path
pwsh deploy\package.ps1 -Zip
```

This writes `cream-app\dist\` with two folders (and `cream-release.zip`):

- `dist\cream-app\`   → upload to `~/cream-app`
- `dist\public_html\` → upload **into** `~/public_html` (merge)

It excludes `.git`, `node_modules`, `tests`, the live `.env` and logs, and writes
the path-adjusted `index.php` into the web folder. No front-end build step is
needed — the public CSS/JS are static files under `public/assets/`.

Flags: `-IncludeVendor` bundles `vendor/` (for a server without Composer);
`-OutDir <path>` changes the output location.

---

## 3. First deployment

1. **Upload** `dist\cream-app\` → `~/cream-app`, and the contents of
   `dist\public_html\` → `~/public_html` (merge with what's there).
2. **Create `.env`** in `~/cream-app`: copy `.env.production.example` to `.env`
   and fill every blank — DB, `APP_URL` (the https canonical origin),
   `UPLOADS_ROOT=/home/<user>/public_html/uploads`, SMTP, real Turnstile keys,
   `MODERATION_EMAIL`. Keep `APP_DEBUG=false`.
3. **Install dependencies** (skip if you used `-IncludeVendor`), inside `~/cream-app`:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
4. **App key** (once — never rotate casually; it protects sessions/encryption):
   ```bash
   php artisan key:generate
   ```
5. **Migrate**:
   ```bash
   php artisan migrate --force
   ```
6. **First admin** (interactive; no hard-coded credentials):
   ```bash
   php artisan cream:create-admin
   ```
7. **Writable storage** — ensure `~/cream-app/storage` and
   `~/cream-app/bootstrap/cache` are writable by the web user (usually already
   fine on Hostinger; `chmod -R ug+rwX storage bootstrap/cache` if you see 500s).
8. **Cache config/routes/views** for production:
   ```bash
   php artisan optimize
   ```
   (Re-run `php artisan optimize` after any future `.env` change — cached config
   does not re-read `.env` until you do.)
9. **Smoke test** by IP/temporary URL before switching DNS: load `/`, a listing,
   `/contact`, submit a test enquiry, and log in at `/staff/login`.

---

## 4. Content cut-over (seed from the static site)

The app seeds its listings, blog posts and the two homepage testimonials from the
**static site's** `data/` + `assets/` — idempotently, and it processes the images
straight into `public_html/uploads` (via `UPLOADS_ROOT`), so there's no separate
image copy.

1. Upload the static repo's `data/` and `assets/` folders to `~` **temporarily**
   (as `~/data` and `~/assets`, siblings of `cream-app`).
2. Inside `~/cream-app`:
   ```bash
   php artisan cream:import-static
   ```
   Safe to re-run; it matches on existing records. "Missing photos" warnings are
   expected where the static site used branded placeholders (`TODO(sam)`).
3. Delete `~/data` and `~/assets` once the import looks right.

> Alternative (no file upload): seed into your local `cream` DB, `mysqldump` it,
> import the dump into the production DB, and copy `public/uploads` →
> `public_html/uploads`. The on-server import above is simpler and preferred.

---

## 5. Go live

1. **Turnstile** — make sure the production keys in `.env` are the real ones from
   the Cloudflare Turnstile dashboard (the committed test keys always pass).
2. **DNS / Cloudflare** — point the domain at the Hostinger site. Keep Cloudflare
   in front (the app reads `CF-IPCountry` for analytics and trusts the TLS from
   Cloudflare). Ensure HTTPS is enforced so `SESSION_SECURE_COOKIE=true` works.
3. **Verify URL parity** — the app serves every URL the static site exposed and
   301s the old `.html` paths. Spot-check a few old links.
4. **Cron** (see §6) must be running for queued mail and the daily purge.

---

## 6. Scheduled tasks (cron)

Add a single hPanel cron job, **every minute**, running Laravel's scheduler with
the 8.4 CLI binary:

```
* * * * * cd /home/<user>/cream-app && php artisan schedule:run >> /dev/null 2>&1
```

That one entry drives everything in `routes/console.php`:

- `queue:work --stop-when-empty` every minute — sends queued enquiry/invitation
  mail and moderation digests (the queue is database-backed; no Redis).
- `comments:digest` hourly — pending-comment digest to the moderation inbox.
- `cream:purge-old-records` daily at 03:30 — deletes old closed/spam enquiries,
  spam comments and analytics page-views per the retention periods in
  **Staff → Settings** (0 = keep forever).

If mail never arrives or the inbox badge never clears, the cron isn't running —
check it first.

---

## 7. Updating an existing deployment

1. Rebuild locally: `pwsh deploy\package.ps1`.
2. (Optional but tidy) `php artisan down` on the server for the brief window.
3. Upload `dist\cream-app\` over `~/cream-app` (this does **not** touch `.env`,
   which the package never contains). Upload `dist\public_html\` over the web root.
4. `composer install --no-dev --optimize-autoloader` (if dependencies changed).
5. `php artisan migrate --force`.
6. `php artisan optimize` (rebuilds the caches).
7. `php artisan up`.

`uploads/` content is never overwritten by a deploy — the package ships an empty
`uploads/` folder only.

---

## 8. Rollback

The static site and its `data/` remain the rollback reference until you're
confident. To roll back during cut-over, repoint DNS / restore the previous
`public_html` and the static files are live again — the dynamic app's database is
left untouched for a later retry.

---

## 9. Troubleshooting

| Symptom | First thing to check |
| --- | --- |
| Every page 500s | `~/cream-app/storage/logs/laravel.log`; usually a missing `.env` value, unwritable `storage/`, or stale cache — run `php artisan optimize:clear` then `php artisan optimize`. |
| DB pages hang then 500 | Database down or wrong `DB_*` — the app can't reach MySQL/MariaDB. |
| Images 404 | `UPLOADS_ROOT` not set to `.../public_html/uploads`, or the import hasn't run. |
| Config change ignored | Cached config — re-run `php artisan optimize`. |
| No mail / badges stuck | The every-minute `schedule:run` cron isn't running (§6). |
| Forms rejected | Real Turnstile keys missing/wrong in `.env`. |

See also [`cream-app/README.md`](cream-app/README.md) for local development.
