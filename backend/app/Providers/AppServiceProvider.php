<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $identity = ($request->header('x-api-key')
                ?? $request->query('api_key')
                ?? $request->ip());

            // por ruta para que no “contamine” entre endpoints en la misma key
            $route = $request->route()?->uri() ?? 'unknown';

            return Limit::perMinute(2)->by($identity . '|' . $route);
        });
    }
}
