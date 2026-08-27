<?php

use App\Http\Controllers\ServiceCategory\ServiceCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/service-categories', [ServiceCategoryController::class, 'index']);
Route::get('/service-categories/{id}', [ServiceCategoryController::class, 'show']);

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::post('/service-categories', [ServiceCategoryController::class, 'store']);
    Route::put('/service-categories/{id}', [ServiceCategoryController::class, 'update']);
    Route::delete('/service-categories/{id}', [ServiceCategoryController::class, 'destroy']);
});
