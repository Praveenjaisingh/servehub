 <?php

// use Illuminate\Http\Request;

// define('LARAVEL_START', microtime(true));

// require __DIR__ . '/../vendor/autoload.php';

// $app = require_once __DIR__ . '/../bootstrap/app.php';

// $app->handleRequest(Request::capture()); 

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Fix Symfony's base-path detection on Vercel
|--------------------------------------------------------------------------
|
| Our entry script physically lives at /api/index.php. Symfony's Request
| computes a "base path" by comparing REQUEST_URI against SCRIPT_NAME, and
| strips that shared prefix before Laravel ever sees it. Because our API
| routes also live under /api, a request to /api/login gets its /api
| prefix silently stripped, so Laravel sees "login" instead of "api/login"
| and matches the wrong route.
|
| Forcing SCRIPT_NAME/PHP_SELF to look like they're at the web root makes
| Symfony compute an empty base path, so the full original path (including
| /api) reaches Laravel's router intact.
|
*/
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());