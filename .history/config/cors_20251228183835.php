<?php

// Parse CORS allowed origins from environment variable or use defaults
$allowedOrigins = env('CORS_ALLOWED_ORIGINS');
if ($allowedOrigins) {
    $allowedOrigins = array_map('trim', explode(',', $allowedOrigins));
} else {
    // Default origins for local development
    $allowedOrigins = ['http://localhost:5173', 'http://localhost:3000'];
}

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],
    'allowed_methods' => ['*'],
    'allowed_origins' => $allowedOrigins,
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
