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

Route::view('/{any}', 'app')->where('any', '.*');
