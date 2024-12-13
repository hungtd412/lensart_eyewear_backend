<?php
return [
    'paths' => ['api/*', 'auth/*', 'sanctum/csrf-cookie', 'users/*'],
    'allowed_methods' => ['*'], // Cho phép tất cả các phương thức (GET, POST, PUT, DELETE, ...)
    'allowed_origins' => ['http://localhost:5173'], // Địa chỉ của frontend
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Cho phép tất cả các header
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Cho phép cookie và xác thực
];
