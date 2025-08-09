<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RateLimiter::for('api-key', function (Request $request) {
            $limit = (int) config('api.rate_limit', 60);

            $rawBy = $request->header('x-api-key') ?? $request->ip();
            $by = is_string($rawBy) ? $rawBy : '';

            return Limit::perMinute($limit)->by($by);
        });
    }
}
