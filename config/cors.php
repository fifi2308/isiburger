<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for handling cross-origin requests.
    | These settings determine which domains can access your application's
    | resources and which HTTP methods are allowed for those requests.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Inclure 'sanctum/csrf-cookie'
              'supports_credentials' => true, // Doit être true


    'allowed_methods' => [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS',  // Allowed HTTP methods
    ],

    'allowed_origins' => [
        '*',  // Allow all domains (you can restrict this to specific domains like ['http://example.com'])
    ],

    'allowed_origins_patterns' => [
        // You can define patterns here (e.g., '/^https:\/\/.*\.example\.com$/')
    ],

    'allowed_headers' => [
        '*',  // Allow all headers (or you can specify specific headers like ['Content-Type', 'Authorization'])
    ],

    'exposed_headers' => [
        // Headers that are exposed to the client-side application
    ],

    'max_age' => 0,  // Cache duration for preflight requests in seconds

    'supports_credentials' => false,  // Allow credentials (cookies, HTTP authentication, etc.)
];
