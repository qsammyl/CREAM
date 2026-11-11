<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

/*
|------------------------------------------------------------------------------
| Front controller for the PRODUCTION split layout (Hostinger)
|------------------------------------------------------------------------------
|
| This file is the ONLY PHP that lives in the web root (public_html). The
| Laravel application itself sits OUTSIDE the web root, in a sibling directory
| named `cream-app`, so none of the app code, .env, vendor or storage is ever
| web-reachable.
|
|     /home/<user>/
|       ├── cream-app/        ← the Laravel app (this repo's cream-app/)
|       │     ├── bootstrap/ vendor/ storage/ ...
|       └── public_html/      ← the web root (document root; cannot be moved)
|             ├── index.php   ← THIS FILE (copied from deploy/public_html.index.php)
|             ├── .htaccess   ← copied from cream-app/public/.htaccess
|             ├── assets/ build/ favicon.svg robots.txt ...  ← public/ contents
|             └── uploads/     ← UPLOADS_ROOT points here
|
| The only difference from Laravel's stock public/index.php is that the two
| require paths point up-and-over into ../cream-app instead of ../. If you place
| the app folder somewhere else, or name it differently, change APP_BASE below.
|
| The deploy/package.ps1 script writes this file into the public_html payload
| automatically — you normally don't edit it by hand.
*/

define('LARAVEL_START', microtime(true));

// Path from this web root to the Laravel app directory (one up, then into it).
$appBase = __DIR__.'/../cream-app';

// Maintenance mode ( php artisan down )...
if (file_exists($maintenance = $appBase.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Composer autoloader + framework bootstrap, from outside the web root.
require $appBase.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $appBase.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
