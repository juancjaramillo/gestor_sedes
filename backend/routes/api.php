<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LocationController;

Route::prefix('v1')
    ->middleware(['api', 'apikey'])
    ->group(function () {
        Route::get('/locations', [LocationController::class, 'index']);
        Route::post('/locations', [LocationController::class, 'store']);
        Route::put('/locations/{location}', [LocationController::class, 'update']);
    });
