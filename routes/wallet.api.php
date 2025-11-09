<?php

use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

//**************************************
// WALLET APIs
//**************************************

Route::group([
    'prefix' => 'wallet'
], function () {
    // Public endpoints - không cần auth
    Route::get('/info', [WalletController::class, 'getWalletInfo']);
    Route::post('/validate-address', [WalletController::class, 'validateAddress']);
    Route::post('/validate-private-key', [WalletController::class, 'validatePrivateKey']);
    Route::get('/balance', [WalletController::class, 'getBalance']);
    Route::get('/contracts', [WalletController::class, 'getContractAddresses']);
    Route::get('/abis', [WalletController::class, 'getContractABIs']);
});

