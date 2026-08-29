<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Everything here just serves the React (Vite) single-page app shell.
| React Router handles client-side routing from there. API endpoints
| live under routes/api.php (mounted at /api by Laravel automatically).
|
*/

Route::get('/__debug-routes', function () {
    $routes = collect(app('router')->getRoutes())->map(function ($r) {
        return implode('|', $r->methods()) . ' ' . $r->uri();
    })->values();
    return response()->json($routes);
});

Route::view('/{any}', 'app')->where('any', '.*');
