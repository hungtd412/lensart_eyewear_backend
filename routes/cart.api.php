<?php

use App\Http\Controllers\CartDetailController;
use Illuminate\Support\Facades\Route;

//**************************************
// GET ALL
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::get('/cart_details', [CartDetailController::class, 'index']);
});


//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/cart_details/create', [CartDetailController::class, 'store']);
});

//**************************************
//  UPDATE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/cart_details/update/{id?}', [CartDetailController::class, 'update']);
});

//**************************************
//  DELETE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/cart_details/delete/{id?}', [CartDetailController::class, 'delete']);
});

Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/cart_details/clear/{cartId?}', [CartDetailController::class, 'clearCart']);
});
//**************************************
//  Tick Product on Carts
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/cart/calculate-total', [CartDetailController::class, 'calculateTotalWithCoupon']);
});

//**************************************
// Click vào Mua Nhanh
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/cart/quick-buy', [CartDetailController::class, 'quickBuy']);
});

//**************************************
// Calculate Total Quantity in Cart
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::get('/cart/total-quantity', [CartDetailController::class, 'calculateTotalQuantity']);
});
