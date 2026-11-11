# CREAM — dynamic site (Laravel app)

The dynamic rebuild of the Credence Real Estate & Asset Management website:
the public database-driven site plus the private staff dashboard at `/staff`.
Lives alongside the original static site (one directory up) until cut-over.

- **Framework:** Laravel 13 · **PHP:** 8.4 (target; matches Hostinger) · **DB:** MySQL/MariaDB
- **Auth:** Laravel Fortify — login, TOTP two-factor + recovery codes, password reset
- **Server layout (production):** app outside `public_html`, only `public/` inside it (Hostinger doc-root can't be moved)

> Phase status and the full plan live in the project root. This README covers
> **local development setup**. For production packaging and the Hostinger
> cut-over, see **[`../DEPLOY.md`](../DEPLOY.md)** and `deploy/package.ps1`.

## Requirements (local, Windows)

| Tool | Version | Notes |
| --- | --- | --- |
| [Laravel Herd](https://herd.laravel.com) | — | Provides PHP 8.4 + Composer. Install PHP **8.4** in Herd › PHP. |
| MariaDB | 10.11 (portable) | Extracted to `C:\Users\<you>\mariadb\mariadb-10.11.19-winx64`, data in `C:\Users\<you>\mariadb-data`. |
| Node.js | 18+ | Only for the front-end build (added in a later phase). |

Herd installs both PHP 8.4 and 8.5. This project targets **8.4** — invoke it
explicitly as `php84` (Composer's `platform.php` is pinned to 8.4 so packages
resolve for the server). Put Herd's bin on PATH for a session:

```powershell
$env:Path = "$env:USERPROFILE\.config\herd\bin;" + $env:Path
```

## Start the local database

The portable MariaDB runs as your user (no admin/service needed). Start it and
leave it running while you work:

```powershell
& "C:\Users\<you>\mariadb\mariadb-10.11.19-winx64\bin\mysqld.exe" `
  --no-defaults --datadir="C:\Users\<you>\mariadb-data" --port=3306 --bind-address=127.0.0.1 --console
```

Databases `cream` (dev) and `cream_test` (tests) and a `cream` user already
exist. To recreate them:

```sql
CREATE DATABASE cream CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE cream_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cream'@'%' IDENTIFIED BY 'cream_local_pw';
GRANT ALL PRIVILEGES ON cream.* TO 'cream'@'%';
GRANT ALL PRIVILEGES ON cream_test.* TO 'cream'@'%';
FLUSH PRIVILEGES;
```

## First-time setup

```powershell
$env:Path = "$env:USERPROFILE\.config\herd\bin;" + $env:Path

copy .env.example .env          # then fill DB_PASSWORD etc.
php84 artisan key:generate
php84 artisan migrate
php84 artisan cream:create-admin   # prompts for name, email, password (min 12 chars)
```

`.env` is never committed. Local mail uses the `log` mailer (written to
`storage/logs/laravel.log`); Turnstile uses Cloudflare's "always passes" test
keys so no real challenge appears.

## Run it

```powershell
php84 artisan serve           # http://127.0.0.1:8000  → /staff/login
```

(Or link the directory into Herd with `herd link` to serve it at `cream-app.test`.)

## Tests

```powershell
php84 artisan test            # feature tests run on in-memory SQLite
```

Covers: login (valid / invalid / inactive / generic error), role permissions
(staff blocked from admin-only routes), the 2FA-required-for-admins gate, the
activity log (login / failed login, IP stored only hashed), and the
`cream:create-admin` command.

## Deployment (production)

The app ships to Hostinger in a **split layout** — app outside `public_html`,
only the web root's contents inside it. Build an upload-ready release locally:

```powershell
pwsh deploy\package.ps1 -Zip      # writes dist\{cream-app,public_html}\ (+ zip)
```

Then follow **[`../DEPLOY.md`](../DEPLOY.md)** (first deploy, content cut-over,
cron, updates, rollback). The tooling only builds the package; you run every
server step yourself.

## What's here (Phase 2)

- Roles (`admin` / `staff`), Fortify auth, TOTP 2FA + recovery codes, password reset
- `cream:create-admin` artisan command (no hard-coded credentials)
- Activity log (create/update/delete/approve/login/failed-login), admin-only viewer
- Security headers + CSP on every response; `/staff` is `noindex`
- Base dashboard shell, profile & security page, in brand fonts/colours

## Notes

- `laravel/passkeys` was pulled in by Fortify and is installed but **disabled**
  (we use TOTP). Its table exists but is unused — a later cleanup candidate.
- Production parity: Hostinger's exact MariaDB version is confirmed after SSH is
  enabled (`SELECT VERSION();`); 10.11 locally is a safe match.
