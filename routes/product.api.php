<?php

use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\Product\ColorController;
use App\Http\Controllers\Product\FeatureController;
use App\Http\Controllers\Product\MaterialController;
use App\Http\Controllers\Product\ShapeController;
use Illuminate\Support\Facades\Route;


//**************************************
// GET ALL
//**************************************
Route::get('/getAllBrands', [BrandController::class, 'index']);
Route::get('/getAllColors', [ColorController::class, 'index']);
Route::get('/getAllShapes', [ShapeController::class, 'index']);
Route::get('/getAllMaterials', [MaterialController::class, 'index']);
Route::get('/getAllFeatures', [FeatureController::class, 'index']);



//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/create', [BrandController::class, 'create']);
    Route::post('/colors/create', [ColorController::class, 'create']);
    Route::post('/shapes/create', [ShapeController::class, 'create']);
    Route::post('/materials/create', [MaterialController::class, 'create']);
    Route::post('/features/create', [FeatureController::class, 'create']);
});



//**************************************
// CREATE MULTIPLE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/createMultiple', [BrandController::class, 'createMultiple']);
    Route::post('/colors/createMultiple', [ColorController::class, 'createMultiple']);
    Route::post('/shapes/createMultiple', [ShapeController::class, 'createMultiple']);
    Route::post('/materials/createMultiple', [MaterialController::class, 'createMultiple']);
    Route::post('/features/createMultiple', [FeatureController::class, 'createMultiple']);
});



//**************************************
//  UPDATE, DELETE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/update/{id?}', [BrandController::class, 'update']);
    Route::post('/brands/delete/{id?}', [BrandController::class, 'delete']);

    Route::post('/colors/update/{id?}', [ColorController::class, 'update']);
    Route::post('/colors/delete/{id?}', [ColorController::class, 'delete']);

    Route::post('/shapes/update/{id?}', [ShapeController::class, 'update']);
    Route::post('/shapes/delete/{id?}', [ShapeController::class, 'delete']);

    Route::post('/materials/update/{id?}', [MaterialController::class, 'update']);
    Route::post('/materials/delete/{id?}', [MaterialController::class, 'delete']);

    Route::post('/features/update/{id?}', [FeatureController::class, 'update']);
    Route::post('/features/delete/{id?}', [FeatureController::class, 'delete']);
});



//**************************************
//  GET BY ID
//**************************************
Route::group([
    'middleware' => ['checkIdParameter'],
], function () {
    Route::get('/brands/getById/{id?}', [BrandController::class, 'getById']);
    Route::get('/colors/getById/{id?}', [ColorController::class, 'getById']);
    Route::get('/shapes/getById/{id?}', [ShapeController::class, 'getById']);
    Route::get('/materials/getById/{id?}', [MaterialController::class, 'getById']);
    Route::get('/features/getById/{id?}', [FeatureController::class, 'getById']);
});

// Route::get('/brands/getByName/{name?}', [BrandController::class, 'getByName']);
