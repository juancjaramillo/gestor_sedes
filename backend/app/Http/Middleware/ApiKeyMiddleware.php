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
        if (!$provided) {
            return $this->unauthorized();
        }

        // Opcional: allowlist por IP (API_IP_ALLOWLIST="127.0.0.1,::1")
        $allowlist = array_filter(array_map('trim', explode(',', (string) env('API_IP_ALLOWLIST', ''))));
        if (!empty($allowlist) && !in_array($request->ip(), $allowlist, true)) {
            return response()->json([
                'error' => ['message' => 'Forbidden', 'code' => 'E_FORBIDDEN'],
            ], 403);
        }

        $expectedPlain = (string) config('api.key');
        $expectedHash  = (string) env('API_KEY_HASH', '');

        $ok = false;

        // Comparación por hash si se define API_KEY_HASH
        if ($expectedHash !== '') {
            $calc = hash('sha256', $provided);
            $ok = hash_equals($expectedHash, $calc);
        } else {
            // Comparación en claro (desarrollo / test)
            if ($expectedPlain !== '') {
                $ok = hash_equals($expectedPlain, (string) $provided);
            }
        }

        if (!$ok) {
            return $this->unauthorized();
        }

        return $next($request);
    }

    private function unauthorized(): Response
    {
        return response()->json([
            'error' => [
                'message' => 'Unauthorized',
                'code'    => 'E_UNAUTHORIZED',
            ],
        ], 401);
    }
}
