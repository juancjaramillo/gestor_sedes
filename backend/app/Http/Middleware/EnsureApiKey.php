<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureApiKey
{
    public function handle(Request $request, Closure $next): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $provided = (string) $request->header('x-api-key', '');
        $expected = (string) config('api.key', '');

        if ($expected === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'error' => [
                    'code' => 'E_UNAUTHORIZED',
                    'message' => 'Missing or invalid API key',
                ],
            ], 401);
        }

        return $next($request);
    }
}
