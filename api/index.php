<?php

use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(HttpKernelContract::class);
$request = Request::capture();
$response = $kernel->handle($request);

if (!headers_sent()) {
    http_response_code($response->getStatusCode());
}

$response->send();
$kernel->terminate($request, $response);