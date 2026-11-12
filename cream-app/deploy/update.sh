#!/usr/bin/env bash
# =============================================================================
# CREAM — server-side update helper (Hostinger shared hosting)
# -----------------------------------------------------------------------------
# Rolls a freshly-uploaded release package onto the LIVE app, preserving the
# server .env, the uploads/ folder and the database. Puts the site in
# maintenance mode during the swap and snapshots the current code for rollback.
#
#   1. Locally:  pwsh deploy\package.ps1 -Zip
#                scp -P 65002 dist\cream-release.zip u530030175@46.202.172.82:~/
#   2. Server:   ./update.sh            # full update (composer + migrate)
#                ./update.sh --fast     # skip composer + migrate (view/asset-only change)
#                ./update.sh --zip ~/some-other.zip
#
# Safe to re-run. It never touches .env, uploads/ or the DB data.
# =============================================================================
set -euo pipefail

# --- config (override by exporting these before running, if paths change) ----
PHP84="${PHP84:-/opt/alt/php84/usr/bin/php}"
DR="${DR:-/home/u530030175/domains/credencerealestateandassetmgt.com}"
APP="$DR/cream-app"
WEB="$DR/public_html"
ZIP="${ZIP:-$HOME/cream-release.zip}"
FAST=0

while [ $# -gt 0 ]; do
  case "$1" in
    --fast) FAST=1 ;;
    --zip)  shift; ZIP="${1:?--zip needs a path}" ;;
    -h|--help) grep -E '^#( |!)' "$0" | sed 's/^# \{0,1\}//'; exit 0 ;;
    *) echo "unknown option: $1" >&2; exit 2 ;;
  esac
  shift
done

ts()  { date +%Y%m%d-%H%M%S; }
log() { printf '\033[36m==>\033[0m %s\n' "$*"; }

# --- pre-flight --------------------------------------------------------------
[ -f "$ZIP" ]            || { echo "release zip not found: $ZIP" >&2; exit 1; }
[ -d "$APP" ]            || { echo "app dir not found: $APP (first deploy? follow DEPLOY.md)" >&2; exit 1; }
[ -x "$PHP84" ]          || { echo "php84 not found at $PHP84" >&2; exit 1; }
command -v composer >/dev/null || { echo "composer not on PATH" >&2; exit 1; }
command -v python3  >/dev/null || { echo "python3 not on PATH (needed to unzip Windows archives)" >&2; exit 1; }

STAGE="$HOME/release-$(ts)"
log "extracting $ZIP -> $STAGE"
mkdir -p "$STAGE"
python3 - "$ZIP" "$STAGE" <<'PY'
import sys, zipfile, os
zip_path, base = sys.argv[1], sys.argv[2]
z = zipfile.ZipFile(zip_path)
for info in z.infolist():
    name = info.filename.replace('\\', '/')        # Windows Compress-Archive uses backslashes
    target = os.path.join(base, name)
    if name.endswith('/'):
        os.makedirs(target, exist_ok=True); continue
    os.makedirs(os.path.dirname(target), exist_ok=True)
    with z.open(info) as s, open(target, 'wb') as d:
        d.write(s.read())
print("extracted")
PY

[ -f "$STAGE/cream-app/artisan" ]      || { echo "staged package missing cream-app/artisan" >&2; exit 1; }
[ -f "$STAGE/public_html/index.php" ]  || { echo "staged package missing public_html/index.php" >&2; exit 1; }

# --- snapshot current code for rollback (vendor/logs excluded to save space) -
SNAP="$HOME/cream-app-prev-$(ts).tar.gz"
log "snapshot current app code -> $SNAP"
tar -czf "$SNAP" -C "$APP" --exclude=vendor --exclude=storage/logs . 2>/dev/null || true

# --- maintenance ON (restored on any exit, success or failure) ---------------
log "maintenance mode ON"
( cd "$APP" && "$PHP84" artisan down --retry=15 ) || true
trap '( cd "$APP" && "$PHP84" artisan up ) || true' EXIT

# --- roll new code over (.env is not in the package, so it is left untouched) -
log "updating app code"
cp -a "$STAGE/cream-app/." "$APP/"
log "updating web payload (uploads/ left intact)"
cp -a "$STAGE/public_html/." "$WEB/"

# keep storage + cache writable
chmod -R u+rwX "$APP/storage" "$APP/bootstrap/cache" 2>/dev/null || true

# --- deps + migrations (unless --fast) ---------------------------------------
if [ "$FAST" -eq 0 ]; then
  log "composer install --no-dev --optimize-autoloader"
  ( cd "$APP" && COMPOSER_MEMORY_LIMIT=-1 "$PHP84" "$(command -v composer)" install --no-dev --optimize-autoloader )
  log "migrate --force"
  ( cd "$APP" && "$PHP84" artisan migrate --force )
else
  log "FAST mode: skipping composer + migrate"
fi

# --- rebuild caches, maintenance OFF -----------------------------------------
log "optimize (rebuild config/route/view caches)"
( cd "$APP" && "$PHP84" artisan optimize:clear && "$PHP84" artisan optimize )
( cd "$APP" && "$PHP84" artisan up ) || true
trap - EXIT

rm -rf "$STAGE"
log "DONE."
log "Rollback this update:  cd \"$APP\" && tar -xzf \"$SNAP\" && \"$PHP84\" artisan optimize:clear && \"$PHP84\" artisan optimize && \"$PHP84\" artisan up"
