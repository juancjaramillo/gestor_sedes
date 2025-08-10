<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request): Limit {
            // Lee header/query sin que el linter se queje
            $headerKey = $request->headers->get('x-api-key');
            $queryKey  = $request->query('api_key');
            $identity  = \is_string($headerKey) ? $headerKey
                       : (\is_string($queryKey) ? $queryKey : $request->getClientIp());

            $route     = $request->route()?->uri() ?? 'unknown';
            $perMinute = (int) \config('api.rate_limit', 60);

            return Limit::perMinute($perMinute)->by(($identity ?? 'unknown') . '|' . $route);
        });
    }
}
