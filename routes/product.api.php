<?php

use App\Http\Controllers\Product\BrandController;
use Illuminate\Support\Facades\Route;


// Route::group([
//     'middleware' => 'auth:sanctum',
// ], function () {
Route::post('/brands/create', [BrandController::class, 'create'])->middleware('auth:sanctum');
Route::get('/getAllBrands', [BrandController::class, 'index']);
Route::group([
    'middleware' => ['checkIdParameter', 'can'],
], function () {
    Route::get('/brands/getById/{id?}', [BrandController::class, 'getById']);
    Route::post('/brands/update/{id?}', [BrandController::class, 'update']);
    Route::post('/brands/delete/{id?}', [BrandController::class, 'delete']);
});
// Route::get('/brands/getByName/{name?}', [BrandController::class, 'getByName']);
// });
