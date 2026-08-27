<?php

use App\Http\Controllers\ProviderProfile\ProviderProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/providers', [ProviderProfileController::class, 'index']);
Route::get('/providers/{id}', [ProviderProfileController::class, 'show']);

Route::middleware(['auth:sanctum', 'role:provider'])->group(function () {
    Route::post('/provider-profile', [ProviderProfileController::class, 'store']);
    Route::put('/provider-profile', [ProviderProfileController::class, 'update']);
    Route::get('/provider-profile/me', [ProviderProfileController::class, 'me']);
    Route::delete('/provider-profile', [ProviderProfileController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::post('/providers/{id}/verify', [ProviderProfileController::class, 'verify']);
});
