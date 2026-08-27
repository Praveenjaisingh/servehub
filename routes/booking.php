<?php

use App\Http\Controllers\Booking\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

    Route::middleware('role:customer')->group(function () {
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    });

    Route::middleware('role:provider')->group(function () {
        Route::get('/provider-bookings', [BookingController::class, 'providerBookings']);
        Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus']);
    });
});
