<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS Paths
    |--------------------------------------------------------------------------
    |
    | Rutas donde se aplicará CORS. Para APIs: "api/*".
    |
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods / Origins / Headers
    |--------------------------------------------------------------------------
    */
    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://127.0.0.1:5173',
        'http://localhost:5173',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers / Max Age / Credentials
    |--------------------------------------------------------------------------
    */
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
