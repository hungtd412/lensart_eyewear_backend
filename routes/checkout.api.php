<?php

use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
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
], function () {
    Route::prefix('/transactions')->group(function () {
        Route::post('/{id?}/create-payment-link', [CheckoutController::class, 'createPaymentLink']);

        //create transaction
        Route::post('orders/{id?}/create', [CheckOutController::class, 'createTransaction']);

        //get transaction's info
        Route::get('/{id}', [CheckOutController::class, 'getPaymentLinkInfoOfOrder']);

        //cancel transaction
        Route::post('/{id}', [CheckOutController::class, 'cancelPaymentLinkOfOrder']);
    });
});

Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager'],
], function () {
    //refresh transaction table
    Route::post('transactions/refresh', [PayOSTransController::class, 'refresh']);
    Route::get('transactions', [PayOSTransController::class, 'refresh']);
});
