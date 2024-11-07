<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\ColorController;
use App\Http\Controllers\Product\FeatureController;
use App\Http\Controllers\Product\MaterialController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ShapeController;
use Illuminate\Support\Facades\Route;


//**************************************
// GET ALL
//**************************************
Route::get('brands', [BrandController::class, 'index']);
Route::get('/colors', [ColorController::class, 'index']);
Route::get('/shapes', [ShapeController::class, 'index']);
Route::get('/materials', [MaterialController::class, 'index']);
Route::get('/features', [FeatureController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/branches', [BranchController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
//check exist parameters in controller (filter)



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
    Route::post('/categories/create', [CategoryController::class, 'create']);
    Route::post('/branches/create', [BranchController::class, 'create']);
    Route::post('/products/create', [ProductController::class, 'create']);
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
    Route::post('/categories/createMultiple', [CategoryController::class, 'createMultiple']);
    Route::post('/products/createMultiple', [ProductController::class, 'createMultiple']);
});



//**************************************
//  UPDATE, DELETE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/update/{id?}', [BrandController::class, 'update']);
    // Route::post('/brands/delete/{id?}', [BrandController::class, 'delete']);

    Route::post('/colors/update/{id?}', [ColorController::class, 'update']);
    // Route::post('/colors/delete/{id?}', [ColorController::class, 'delete']);

    Route::post('/shapes/update/{id?}', [ShapeController::class, 'update']);
    // Route::post('/shapes/delete/{id?}', [ShapeController::class, 'delete']);

    Route::post('/materials/update/{id?}', [MaterialController::class, 'update']);
    // Route::post('/materials/delete/{id?}', [MaterialController::class, 'delete']);

    Route::post('/features/update/{id?}', [FeatureController::class, 'update']);
    // Route::post('/features/delete/{id?}', [FeatureController::class, 'delete']);

    Route::post('/categories/update/{id?}', [CategoryController::class, 'update']);
    // Route::post('/categories/delete/{id?}', [CategoryController::class, 'delete']);

    Route::post('/branches/update/{id?}', [BranchController::class, 'update']);
    // Route::post('/branches/delete/{id?}', [BranchController::class, 'delete']);

    Route::post('/products/update/{id?}', [ProductController::class, 'update']);
    // Route::post('/products/delete/{id?}', [ProductController::class, 'delete']);
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
    Route::get('/categories/getById/{id?}', [CategoryController::class, 'getById']);
    Route::get('/branches/getById/{id?}', [BranchController::class, 'getById']);
    Route::get('/products/getById/{id?}', [ProductController::class, 'getById']);
});


//**************************************
//  SWITCH STATUS
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/switch-status/{id?}', [BrandController::class, 'switchStatus']);

    Route::post('/colors/switch-status/{id?}', [ColorController::class, 'switchStatus']);

    Route::post('/shapes/switch-status/{id?}', [ShapeController::class, 'switchStatus']);

    Route::post('/materials/switch-status/{id?}', [MaterialController::class, 'switchStatus']);

    Route::post('/features/switch-status/{id?}', [FeatureController::class, 'switchStatus']);

    Route::post('/categories/switch-status/{id?}', [CategoryController::class, 'switchStatus']);

    Route::post('/branches/switch-status/{id?}', [BranchController::class, 'switchStatus']);

    Route::post('/products/switch-status/{id?}', [ProductController::class, 'switchStatus']);
});
// Route::get('/brands/getByName/{name?}', [BrandController::class, 'getByName']);
