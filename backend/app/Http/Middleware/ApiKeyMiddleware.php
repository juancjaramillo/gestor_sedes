<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $provided = $request->header('x-api-key') ?? $request->query('api_key');
        $expected = (string) config('api.key');

        if (! $expected || ! hash_equals($expected, (string) $provided)) {
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
