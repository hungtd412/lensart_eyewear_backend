<?php

use App\Http\Controllers\CheckOutController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

//**************************************
//  PAYOS
//**************************************

Route::post('/create-payment-link', [CheckoutController::class, 'createPaymentLink']);

Route::prefix('/order')->group(function () {
    Route::get('/{id}', [CheckOutController::class, 'getPaymentLinkInfoOfOrder']);
    Route::put('/{id}', [CheckOutController::class, 'cancelPaymentLinkOfOrder']);
});
