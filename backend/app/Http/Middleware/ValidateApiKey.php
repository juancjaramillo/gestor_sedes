<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $provided = (string) $request->header('x-api-key', '');
        $expected = (string) config('api.key', '');

        if ($expected === '' || $provided === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'error' => [
                    'code' => 'E_UNAUTHORIZED',
                    'message' => 'Missing or invalid API key.',
                ],
            ], 401);
        }

        return $next($request);
    }
}
