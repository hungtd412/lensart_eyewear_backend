<?php

use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\PayOSTransController;
use Illuminate\Support\Facades\Route;





//**************************************
//  MOMO
//**************************************
//ko dung
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
    //pass returnUrl and cancelUrl
    //example: "returnUrl": "http://127.0.0.1:8000/", "cancelUrl": "http://127.0.0.1:8000/"
    Route::post('/orders/{id?}/create', [CheckOutController::class, 'createTransaction']);
    Route::post('/update/order/{id}', [PayOSTransController::class, 'update']);

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
    Route::get('/transactions', [PayOSTransController::class, 'index']);
    //ko can truyen j ca, vì chỉ fresh lai trang giao dich
    Route::post('/transactions/refresh', [PayOSTransController::class, 'refresh']);
});
