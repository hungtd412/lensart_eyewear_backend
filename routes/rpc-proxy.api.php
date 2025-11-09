<?php

use App\Http\Controllers\RPCProxyController;
use Illuminate\Support\Facades\Route;

//**************************************
// RPC PROXY APIs (to avoid CORS issues)
//**************************************

Route::group([
    'prefix' => 'rpc-proxy'
], function () {
    // Public endpoints - không cần auth
    Route::post('/proxy', [RPCProxyController::class, 'proxy']);
    Route::get('/rpc-url', [RPCProxyController::class, 'getRpcUrl']);
});

