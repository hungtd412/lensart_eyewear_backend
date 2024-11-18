<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartDetailController;
use App\Models\CartDetail;
use Illuminate\Support\Facades\Route;

//**************************************
// GET ALL
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::get('/carts', [CartController::class, 'index']);
    Route::get('/cart_details', [CartDetailController::class, 'index']);
});


//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/carts/create', [CartController::class, 'store']);
    Route::post('/cart_details/create', [CartDetailController::class, 'store']);
});

//**************************************
//  UPDATE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/carts/update/{id?}', [CartController::class, 'update']);

    // Update số lượng trong Cart
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
