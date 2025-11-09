<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web3 Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Web3 and blockchain interactions.
    |
    */

    'default_network' => env('WEB3_DEFAULT_NETWORK', 'sepolia'),

    /*
    |--------------------------------------------------------------------------
    | Network Configurations
    |--------------------------------------------------------------------------
    */

    'networks' => [
        'sepolia' => [
            'chain_id' => 11155111,
            'name' => 'Sepolia Test Network',
            'rpc_url' => env('SEPOLIA_RPC_URL', ''),
            'explorer_url' => env('SEPOLIA_EXPLORER_URL', 'https://sepolia.etherscan.io'),
        ],
        'mainnet' => [
            'chain_id' => 1,
            'name' => 'Ethereum Mainnet',
            'rpc_url' => env('MAINNET_RPC_URL', ''),
            'explorer_url' => env('MAINNET_EXPLORER_URL', 'https://etherscan.io'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Contract Addresses
    |--------------------------------------------------------------------------
    |
    | Contract addresses are loaded from contracts/exports/frontend-config-{network}.json
    | These are fallback values if config files are not found.
    |
    */

    'contracts' => [
        'sepolia' => [
            'LENSToken' => env('SEPOLIA_LENS_TOKEN', ''),
            'LensArtPayment' => env('SEPOLIA_PAYMENT_CONTRACT', ''),
            'LensArtOrderNFT' => env('SEPOLIA_NFT_CONTRACT', ''),
        ],
        'mainnet' => [
            'LENSToken' => env('MAINNET_LENS_TOKEN', ''),
            'LensArtPayment' => env('MAINNET_PAYMENT_CONTRACT', ''),
            'LensArtOrderNFT' => env('MAINNET_NFT_CONTRACT', ''),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Transaction Settings
    |--------------------------------------------------------------------------
    */

    'transaction' => [
        'timeout' => env('WEB3_TRANSACTION_TIMEOUT', 30), // seconds
        'confirmations' => env('WEB3_CONFIRMATIONS', 1),
        'gas_limit' => env('WEB3_GAS_LIMIT', 100000),
    ],
];

