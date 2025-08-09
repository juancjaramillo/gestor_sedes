<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LocationController;

Route::prefix('v1')
   ->middleware(['apikey', 'throttle:api'])  
    ->group(function () {
        Route::get('/locations', [LocationController::class, 'index']);
        Route::post('/locations', [LocationController::class, 'store']);
        Route::post('/locations/{location}', [LocationController::class, 'update']);
    });
