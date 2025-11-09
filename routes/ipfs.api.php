<?php

use App\Http\Controllers\IPFSController;
use Illuminate\Support\Facades\Route;

//**************************************
// IPFS APIs
//**************************************

Route::group([
    'prefix' => 'ipfs'
], function () {
    // Public endpoints - có thể cần auth tùy use case
    Route::post('/upload', [IPFSController::class, 'upload']);
    Route::post('/upload-json', [IPFSController::class, 'uploadJSON']);
    Route::get('/retrieve/{hash}', [IPFSController::class, 'retrieve'])
        ->where('hash', '[a-zA-Z0-9]+');
    Route::get('/retrieve-json/{hash}', [IPFSController::class, 'retrieveJSON'])
        ->where('hash', '[a-zA-Z0-9]+');
    Route::get('/gateway/{hash}', [IPFSController::class, 'getGatewayUrl'])
        ->where('hash', '[a-zA-Z0-9]+');
    Route::post('/pin', [IPFSController::class, 'pin']);
});

