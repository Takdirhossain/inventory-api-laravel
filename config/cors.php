<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | This file controls the cross-origin resource sharing (CORS) settings.
    | Adjust the allowed paths, origins, methods, and headers based on
    | your frontend applications and API endpoints.
    |
    */

    'paths' => [
        'api/*',               // All API routes
        'admin/*',             // If you have admin routes
        'sanctum/csrf-cookie', // Needed for Sanctum auth (if used)
    ],

    'allowed_methods' => ['*'], // Allow all methods: GET, POST, PUT, PATCH, DELETE, OPTIONS

    'allowed_origins' => [
        'http://localhost:3000',   // React local dev
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Allow all headers (Authorization, Content-Type, etc.)

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Needed if you use cookies / Sanctum
];
