<?php

use App\Http\Controllers\NFTController;
use Illuminate\Support\Facades\Route;

//**************************************
// NFT APIs
//**************************************

Route::group([
    'prefix' => 'nft'
], function () {
    // Public endpoints
    Route::get('/contract', [NFTController::class, 'getContractInfo']);
    Route::post('/prepare-mint', [NFTController::class, 'prepareMint']);
    Route::get('/info/{tokenId}', [NFTController::class, 'getNFTInfo'])
        ->where('tokenId', '[0-9]+');
    Route::get('/owner', [NFTController::class, 'getOwnerNFTs']);
    Route::get('/order/{orderId}', [NFTController::class, 'getTokenIdByOrder'])
        ->where('orderId', '[0-9]+');
});

