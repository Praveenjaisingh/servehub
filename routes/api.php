<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Each module owns its own route file, grouped by entity to match the
| Controller/Service/Repository structure under app/. See routes/*.php.
|
*/

use Illuminate\Support\Facades\Route;

Route::any('/echo-debug', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'path' => $request->path(),
        'getRequestUri' => $request->getRequestUri(),
        'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? null,
        'PATH_INFO' => $_SERVER['PATH_INFO'] ?? null,
        'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? null,
        'method' => $request->method(),
    ]);
});

require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/providerProfile.php';
require __DIR__.'/serviceCategory.php';
require __DIR__.'/service.php';
require __DIR__.'/availability.php';
require __DIR__.'/booking.php';
require __DIR__.'/review.php';
require __DIR__.'/notification.php';
require __DIR__.'/adminDashboard.php';