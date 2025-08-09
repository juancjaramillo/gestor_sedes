<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RateLimiter::for('api-key', function (Request $request) {
            $limit = (int) config('api.rate_limit', 60);
            $by = $request->header('x-api-key') ?: $request->ip();
            return Limit::perMinute($limit)->by((string) $by);
        });
    }
}
