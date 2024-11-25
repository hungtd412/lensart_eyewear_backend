<?php

use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\PayOSTransController;
use Illuminate\Support\Facades\Route;





//**************************************
//  MOMO
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/momo_payment/atm', [CheckOutController::class, 'momoATMPayment']);
    Route::post('/momo_payment/qr', [CheckOutController::class, 'momoQRPayment']);
});



//**************************************
//  PAYOS
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer', 'checkIdParameter'],
    'prefix' => 'transactions',
], function () {
    // Route::post('/{id?}/create-payment-link', [CheckoutController::class, 'createPaymentLink']);

    //create transaction
    Route::post('/orders/{id?}/create', [CheckOutController::class, 'createTransaction']);

    //get transaction's info
    Route::get('/{id?}/info', [CheckOutController::class, 'getPaymentLinkInfoOfOrder']);

    //cancel transaction
    // Route::post('/{id?}/cancel', [CheckOutController::class, 'cancelPaymentLinkOfOrder']);
});



//**************************************
//  REFRESH TRANSACTION PAGE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager'],
], function () {
    Route::get('/transactions/all', [PayOSTransController::class, 'index']);
    Route::post('/transactions/refresh', [PayOSTransController::class, 'refresh']);
});
