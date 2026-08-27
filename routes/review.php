<?php

use App\Http\Controllers\Review\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/providers/{providerId}/reviews', [ReviewController::class, 'forProvider']);

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:customer')->group(function () {
        Route::post('/reviews', [ReviewController::class, 'store']);
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
    });
});
