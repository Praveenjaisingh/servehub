<?php

use App\Http\Controllers\Availability\AvailabilityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:provider'])->group(function () {
    Route::get('/availability', [AvailabilityController::class, 'index']);
    Route::post('/availability', [AvailabilityController::class, 'store']);
    Route::delete('/availability/{id}', [AvailabilityController::class, 'destroy']);
});
