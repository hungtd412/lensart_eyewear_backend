<?php

use App\Http\Controllers\CryptoPaymentController;
use Illuminate\Support\Facades\Route;

//**************************************
// CRYPTO PAYMENT APIs
//**************************************

Route::group([
    'prefix' => 'crypto-payment'
], function () {
    // Public endpoint - Lấy payment info (không cần auth)
    Route::get('/{orderId}/info', [CryptoPaymentController::class, 'getPaymentInfo'])
        ->where('orderId', '[0-9]+');
    
    // Protected endpoints - Cần auth
    Route::middleware(['auth:sanctum', 'can:is-customer'])->group(function () {
        // Tạo payment request cho crypto payment
        Route::post('/{orderId}/create', [CryptoPaymentController::class, 'createPaymentRequest'])
            ->where('orderId', '[0-9]+');
        
        // Xác nhận payment đã hoàn thành
        Route::post('/{orderId}/confirm', [CryptoPaymentController::class, 'confirmPayment'])
            ->where('orderId', '[0-9]+');
    });
});

