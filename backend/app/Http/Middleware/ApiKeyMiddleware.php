<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Puede venir por header o query param; ambos pueden ser array|string|null
        $rawProvided = $request->headers->get('x-api-key') ?? $request->query('api_key');
        $provided = is_string($rawProvided) ? $rawProvided : '';

        // La key en config (de .env/.env.testing) también puede ser null o array si está mal definida
        $rawExpected = config('api.key');
        $expected = is_string($rawExpected) ? $rawExpected : '';

        // Rechaza si falta config o falta key provista o no coincide
        if ($expected === '' || $provided === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'error' => [
                    'message' => 'Unauthorized',
                    'code' => 'E_UNAUTHORIZED',
                ],
            ], 401);
        }

        return $next($request);
    }
}
