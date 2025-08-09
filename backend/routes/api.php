<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LocationController;

Route::prefix('v1')
    ->middleware(['api', 'api.key', 'throttle:api'])
    ->group(function () {
        Route::get('/locations', [LocationController::class, 'index']);
        Route::post('/locations', [LocationController::class, 'store']);
        Route::post('/locations/{location}', [LocationController::class, 'update']); // opcional PUT, aquí POST por simplicidad PowerShell
    });
