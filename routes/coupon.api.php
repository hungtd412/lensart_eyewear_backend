<?php

use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;


//**************************************
// GET ALL
//**************************************
Route::get('coupons', [CouponController::class, 'index']);



//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin'],
], function () {
    //name code quantity discount_price
    Route::post('/coupons/create', [CouponController::class, 'store']);
});



//**************************************
//  UPDATE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    //name code quantity discount_price status
    Route::post('/coupons/update/{id?}', [CouponController::class, 'update']);
});



//**************************************
//  GET BY ID
//**************************************
Route::group([
    'middleware' => ['checkIdParameter'],
], function () {
    Route::get('/coupons/getById/{id?}', [CouponController::class, 'getById']);
});



//**************************************
//  SWITCH STATUS
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/coupons/switch-status/{id?}', [CouponController::class, 'switchStatus']);
});
