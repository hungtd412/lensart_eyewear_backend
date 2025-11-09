<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IPFS Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for IPFS (InterPlanetary File System) integration.
    | Supports Pinata, Infura IPFS, or local IPFS node.
    |
    */

    'api_url' => env('IPFS_API_URL', 'https://api.pinata.cloud'),

    'api_key' => env('IPFS_API_KEY', ''),

    'api_secret' => env('IPFS_API_SECRET', ''),

    'gateway_url' => env('IPFS_GATEWAY_URL', 'https://gateway.pinata.cloud/ipfs/'),

    /*
    |--------------------------------------------------------------------------
    | IPFS Provider
    |--------------------------------------------------------------------------
    |
    | Supported providers: 'pinata', 'infura', 'local'
    |
    */

    'provider' => env('IPFS_PROVIDER', 'pinata'),

    /*
    |--------------------------------------------------------------------------
    | Infura IPFS Configuration (if using Infura)
    |--------------------------------------------------------------------------
    */

    'infura' => [
        'project_id' => env('IPFS_INFURA_PROJECT_ID', ''),
        'project_secret' => env('IPFS_INFURA_PROJECT_SECRET', ''),
        'endpoint' => env('IPFS_INFURA_ENDPOINT', 'https://ipfs.infura.io:5001'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Local IPFS Node Configuration (if using local node)
    |--------------------------------------------------------------------------
    */

    'local' => [
        'host' => env('IPFS_LOCAL_HOST', '127.0.0.1'),
        'port' => env('IPFS_LOCAL_PORT', 5001),
        'protocol' => env('IPFS_LOCAL_PROTOCOL', 'http'),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Limits
    |--------------------------------------------------------------------------
    */

    'max_file_size' => env('IPFS_MAX_FILE_SIZE', 10240), // KB

    'allowed_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'application/json',
        'text/plain',
    ],
];

