<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


//**************************************
// GET ALL
//**************************************
Route::get('orders', [OrderController::class, 'index'])->middleware(['auth:sanctum', 'can:is-admin-manager']);



//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/orders/create', [OrderController::class, 'store']);
});



//**************************************
//  UPDATE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/orders/update/{id?}', [OrderController::class, 'update']);
});



//**************************************
//  GET BY ID
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum'],
], function () {
    Route::get('/orders/getById/{id?}', [OrderController::class, 'getById']);
});



//**************************************
//  CHANGE STATUS
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin-manager'],
], function () {
    Route::post('/orders/change-order-status/{id?}', [OrderController::class, 'changeOrderStatus']);

    Route::post('/orders/change-payment-status/{id?}', [OrderController::class, 'changePaymentStatus']);

    Route::post('/orders/switch-status/{id?}', [OrderController::class, 'switchStatus']);
});



//**************************************
//  CANCEL ORDER
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum'],
], function () {
    Route::post('/orders/cancel/{id?}', [OrderController::class, 'cancel']);
});
