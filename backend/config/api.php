<?php

return [
   'key' => env('API_KEY', ''),
    'version' => 'v1',
    'rate_limit' => (int) env('API_RATE_LIMIT', 60),
    'cache_ttl' => 30, // segundos para cache de listado
];
