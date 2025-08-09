<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Middleware\HandleCors; // <--- IMPORTANTE: CORS
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias de middlewares (Laravel 12)
        $middleware->alias([
            'apikey' => \App\Http\Middleware\ApiKeyMiddleware::class,
            // Para rate limiting por API Key usa en rutas: 'throttle:api-key' (definido via RateLimiter en AppServiceProvider)
        ]);

        // ⬇⬇⬇ Asegurar CORS en toda la app (o al menos en API)
        // En L12, se registra desde bootstrap/app.php
        $middleware->append(HandleCors::class);

        // Si prefieres que CORS aplique SOLO al grupo API, puedes usar:
        // $middleware->api(prepend: [HandleCors::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Forzar JSON para rutas de API
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        // Renderizador único clasificado por tipo
        $exceptions->render(function (Throwable $e, Request $request) {

            // 1) 422 - Validaciones
            if ($e instanceof ValidationException) {
                return response()->json([
                    'error' => [
                        'message' => 'Invalid parameters',
                        'code'    => 'E_INVALID_PARAM',
                        'details' => $e->errors(),
                    ],
                ], 422);
            }

            // 2) 401 - No autenticado (Laravel)
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'error' => [
                        'message' => 'Unauthorized',
                        'code'    => 'E_UNAUTHORIZED',
                    ],
                ], 401);
            }

            // 3) 403 - Prohibido (Laravel)
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'error' => [
                        'message' => 'Forbidden',
                        'code'    => 'E_FORBIDDEN',
                    ],
                ], 403);
            }

            // 4) Errores HTTP (404/403/401/...) sin referenciar tipos de Symfony:
            $httpStatus = null;
            if (is_callable([$e, 'getStatusCode'])) {
                /** @var callable $call */
                $call = [$e, 'getStatusCode'];
                $httpStatus = (int) call_user_func($call);
            }

            if ($httpStatus !== null && $httpStatus >= 400 && $httpStatus <= 599) {
                $code = match ($httpStatus) {
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
                ], $httpStatus);
            }

            // 5) Fallback 500
            return response()->json([
                'error' => [
                    'message' => 'Server Error',
                    'code'    => 'E_SERVER_ERROR',
                ],
            ], 500);
        });
    })
    ->create();
