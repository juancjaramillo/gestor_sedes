<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        RateLimiter::for('api', function (Request $request) {
            $perMinute = (int) config('api.rate_limit', 60);
            $key = $request->header('x-api-key') ?: $request->ip();

            return [Limit::perMinute($perMinute)->by($key)];
        });
    }
}
