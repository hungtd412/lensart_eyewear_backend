<?php

use App\Http\Controllers\Product\BrandController;
use Illuminate\Support\Facades\Route;


//**************************************
// GET ALL
//**************************************
Route::get('/getAllBrands', [BrandController::class, 'index']);



//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/create', [BrandController::class, 'create'])->middleware('auth:sanctum');
});



//**************************************
//  UPDATE, DELETE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/update/{id?}', [BrandController::class, 'update']);
    Route::post('/brands/delete/{id?}', [BrandController::class, 'delete']);
});



//**************************************
//  GET BY ID
//**************************************
Route::group([
    'middleware' => ['checkIdParameter'],
], function () {
    Route::get('/brands/getById/{id?}', [BrandController::class, 'getById']);
});

// Route::get('/brands/getByName/{name?}', [BrandController::class, 'getByName']);
