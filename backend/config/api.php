<?php

return [
    'key' => env('API_KEY', ''),
    'rate_limit' => (int) env('API_RATE_LIMIT', 60),
    'cache_ttl' => (int) env('API_CACHE_TTL', 60),
];
