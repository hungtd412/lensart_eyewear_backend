<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

//**************************************
// TRANSACTION APIs
//**************************************

Route::group([
    'prefix' => 'transaction'
], function () {
    // Public endpoints - không cần auth (vì frontend sẽ ký transaction)
    Route::post('/prepare/approve', [TransactionController::class, 'prepareApprove']);
    Route::post('/prepare/payment', [TransactionController::class, 'prepareInitiatePayment']);
    Route::post('/send', [TransactionController::class, 'sendTransaction']);
    Route::get('/status/{txHash}', [TransactionController::class, 'getTransactionStatus'])
        ->where('txHash', '0x[a-fA-F0-9]{64}');
    Route::post('/read-contract', [TransactionController::class, 'readContract']);
});

