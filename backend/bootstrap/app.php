<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
       
        $middleware->alias([
            'key' => \App\Http\Middleware\EnsureApiKey::class,
        ]);

      
        $middleware->api(prepend: [
            'throttle:api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
   
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => [
                        'code'    => 'E_INVALID_PARAM',
                        'message' => 'Validation failed',
                        'details' => $e->errors(),
                    ],
                ], 422);
            }
        });
    })
    ->withSchedule(function () {})
    ->create();


RateLimiter::for('api', function (Request $request) {
    $by   = $request->header('x-api-key') ?: $request->ip();
    $max  = (int) config('api.rate_limit', 60);

    return [ Limit::perMinute($max)->by($by) ];
});
