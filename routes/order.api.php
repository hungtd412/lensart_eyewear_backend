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
    'middleware' => ['checkIdParameter'],
], function () {
    Route::get('/orders/getById/{id?}', [OrderController::class, 'getById']);
});



//**************************************
//  SWITCH STATUS
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/orders/switch-status/{id?}', [OrderController::class, 'switchStatus']);
});
