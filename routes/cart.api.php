<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartDetailController;
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
