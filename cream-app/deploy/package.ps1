#requires -Version 5.1
<#
.SYNOPSIS
    Assemble an upload-ready release of the CREAM Laravel app for Hostinger.

.DESCRIPTION
    Produces two folders under an output directory (default: cream-app\dist):

        dist\cream-app\     -> upload to  ~/cream-app      (outside public_html)
        dist\public_html\   -> upload INTO ~/public_html   (merge with the docroot)

    The web folder gets the path-adjusted front controller from
    deploy\public_html.index.php. Dev-only files (.git, node_modules, tests,
    .env, logs) are left out. This script ONLY builds the package locally — it
    never connects to or touches the live server. See DEPLOY.md for the steps
    you run afterwards.

.PARAMETER OutDir
    Where to write the release. Defaults to cream-app\dist (git-ignored).

.PARAMETER IncludeVendor
    Bundle the current vendor/ folder too (for a server without Composer).
    NOTE: it may contain dev dependencies — prefer running
    `composer install --no-dev --optimize-autoloader` on the server instead.

.PARAMETER Zip
    Also produce dist\cream-release.zip containing both folders.

.EXAMPLE
    pwsh deploy\package.ps1 -Zip
#>
[CmdletBinding()]
param(
    [string]$OutDir,
    [switch]$IncludeVendor,
    [switch]$Zip
)

$ErrorActionPreference = 'Stop'

# cream-app\ (this script lives in cream-app\deploy\)
$AppRoot = Split-Path -Parent $PSScriptRoot
if (-not $OutDir) { $OutDir = Join-Path $AppRoot 'dist' }

$releaseApp = Join-Path $OutDir 'cream-app'
$releaseWeb = Join-Path $OutDir 'public_html'

function Invoke-Robocopy {
    param([string[]]$RoboArgs)
    & robocopy @RoboArgs | Out-Null
    # robocopy exit codes 0-7 are success; 8+ is a real failure.
    if ($LASTEXITCODE -ge 8) { throw "robocopy failed (exit $LASTEXITCODE): $($RoboArgs -join ' ')" }
    $global:LASTEXITCODE = 0
}

Write-Host "CREAM release packager" -ForegroundColor Cyan
Write-Host "  app root : $AppRoot"
Write-Host "  output   : $OutDir"
Write-Host ""

# 1. Clean output.
if (Test-Path $OutDir) { Remove-Item $OutDir -Recurse -Force }
New-Item -ItemType Directory -Path $releaseApp, $releaseWeb | Out-Null

# 2. App payload -> dist\cream-app (everything bar dev cruft and the live .env).
$excludeDirs = @('.git', '.github', '.idea', '.vscode', '.zed', '.nova',
    'node_modules', 'tests', '.phpunit.cache', 'dist')
if (-not $IncludeVendor) { $excludeDirs += 'vendor' }

$excludeFiles = @('.env', '.env.backup', '*.log', '.phpunit.result.cache',
    '_ide_helper.php', 'auth.json', '.DS_Store', 'Thumbs.db', '.phpactor.json')

Write-Host "- copying application files..."
$appArgs = @($AppRoot, $releaseApp, '/E', '/NFL', '/NDL', '/NJH', '/NJS', '/NP', '/XD') +
    $excludeDirs + @('/XF') + $excludeFiles
Invoke-Robocopy $appArgs

# Ship an empty, writable storage tree (no cached/compiled/log leftovers).
foreach ($junk in @('framework\cache\data', 'framework\sessions', 'framework\views', 'logs')) {
    $p = Join-Path $releaseApp "storage\$junk"
    if (Test-Path $p) { Get-ChildItem $p -File -Recurse | Remove-Item -Force -ErrorAction SilentlyContinue }
}

# 3. Web payload -> dist\public_html (contents of public/, minus uploaded content).
Write-Host "- copying web root (public/) ..."
Invoke-Robocopy @((Join-Path $AppRoot 'public'), $releaseWeb,
    '/E', '/NFL', '/NDL', '/NJH', '/NJS', '/NP', '/XD', 'uploads', '/XF', 'index.php')

# Keep an empty uploads/ so the folder exists in the docroot (UPLOADS_ROOT).
New-Item -ItemType Directory -Path (Join-Path $releaseWeb 'uploads') -Force | Out-Null

# Path-adjusted front controller (points up into ../cream-app).
Copy-Item (Join-Path $PSScriptRoot 'public_html.index.php') (Join-Path $releaseWeb 'index.php') -Force

# 4. Optional zip.
if ($Zip) {
    $zipPath = Join-Path $OutDir 'cream-release.zip'
    Write-Host "- zipping -> $zipPath"
    Compress-Archive -Path $releaseApp, $releaseWeb -DestinationPath $zipPath -Force
}

Write-Host ""
Write-Host "Done." -ForegroundColor Green
Write-Host "  $releaseApp"
Write-Host "  $releaseWeb"
if (-not $IncludeVendor) {
    Write-Host ""
    Write-Host "vendor/ was NOT bundled. On the server, inside ~/cream-app, run:" -ForegroundColor Yellow
    Write-Host "  composer install --no-dev --optimize-autoloader"
}
Write-Host ""
Write-Host "Next steps: see DEPLOY.md (first deploy, cron, cut-over)."
