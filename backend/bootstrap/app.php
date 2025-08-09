<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'apikey' => \App\Http\Middleware\ApiKeyMiddleware::class,
        ]);

        // CORS solo para API (elige una de estas dos líneas: api() o append())
        $middleware->api(prepend: [HandleCors::class]);
        // $middleware->append(HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'error' => [
                    'message' => 'Invalid parameters',
                    'code'    => 'E_INVALID_PARAM',
                    'details' => $e->errors(),
                ],
            ], 422);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'error' => [
                    'message' => 'Unauthorized',
                    'code'    => 'E_UNAUTHORIZED',
                ],
            ], 401);
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return response()->json([
                'error' => [
                    'message' => 'Forbidden',
                    'code'    => 'E_FORBIDDEN',
                ],
            ], 403);
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            $status = $e->getStatusCode();
            $code = match ($status) {
                401 => 'E_UNAUTHORIZED',
                403 => 'E_FORBIDDEN',
                404 => 'E_NOT_FOUND',
                default => 'E_HTTP_ERROR',
            };

            return response()->json([
                'error' => [
                    'message' => $e->getMessage() ?: 'HTTP Error',
                    'code'    => $code,
                ],
            ], $status);
        });

        $exceptions->render(function (\Throwable $e, Request $request) {
            return response()->json([
                'error' => [
                    'message' => 'Server Error',
                    'code'    => 'E_SERVER_ERROR',
                ],
            ], 500);
        });
    })
    ->create();
