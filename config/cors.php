<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    /*
    | Ensure CORS headers are applied to API, Sanctum and common
    | authentication endpoints (register/login) used by the SPA.
    */
    'paths' => ['api/*', 'sanctum/*', 'sanctum/csrf-cookie', 'register', 'login', 'logout'],

    'allowed_methods' => ['*'],

    /*
    | Use a specific origin list when using cookie-based (credentialed)
    | requests. Browsers will reject responses that use a wildcard
    | `Access-Control-Allow-Origin: *` when the request is sent
    | with credentials (cookies). We read allowed origins from an env
    | variable so you can include both `localhost` and `127.0.0.1`.
    */
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://127.0.0.1:8000,http://localhost:8000')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
    | When using Sanctum / cookie auth the frontend must send
    | credentials and the server must support them.
    */
    'supports_credentials' => true,

];
