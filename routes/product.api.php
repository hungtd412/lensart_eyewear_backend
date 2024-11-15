<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\Product\CategoryController;
// use App\Http\Controllers\Product\ColorController;
use App\Http\Controllers\Product\FeatureController;
use App\Http\Controllers\Product\MaterialController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductDetailController;
use App\Http\Controllers\Product\ProductFeatureController;
use App\Http\Controllers\Product\ProductImageController;
use App\Http\Controllers\Product\ShapeController;
use Illuminate\Support\Facades\Route;


//**************************************
// GET ALL
//**************************************
Route::get('brands', [BrandController::class, 'index']);
// Route::get('/colors', [ColorController::class, 'index']);
Route::get('/shapes', [ShapeController::class, 'index']);
Route::get('/materials', [MaterialController::class, 'index']);
Route::get('/features', [FeatureController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/branches', [BranchController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product-features', [ProductFeatureController::class, 'index']);
Route::get('/product-images', [ProductImageController::class, 'index']);
Route::get('/product-details', [ProductDetailController::class, 'index']);



//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/create', [BrandController::class, 'store']);
    // Route::post('/colors/create', [ColorController::class, 'store']);
    Route::post('/shapes/create', [ShapeController::class, 'store']);
    Route::post('/materials/create', [MaterialController::class, 'store']);
    Route::post('/features/create', [FeatureController::class, 'store']);
    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::post('/branches/create', [BranchController::class, 'store']);
    Route::post('/products/create', [ProductController::class, 'store']);
    Route::post('/product-features/create', [ProductFeatureController::class, 'store']);
    Route::post('/product-images/create', [ProductImageController::class, 'store']);
});



//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager'],
], function () {
    Route::post('/product-details/create', [ProductDetailController::class, 'store']);
    Route::post('/product-details/createForAllBranch', [ProductDetailController::class, 'storeForAllBranch']);
});



//**************************************
// CREATE MULTIPLE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/createMultiple', [BrandController::class, 'createMultiple']);
    // Route::post('/colors/createMultiple', [ColorController::class, 'createMultiple']);
    Route::post('/shapes/createMultiple', [ShapeController::class, 'createMultiple']);
    Route::post('/materials/createMultiple', [MaterialController::class, 'createMultiple']);
    Route::post('/features/createMultiple', [FeatureController::class, 'createMultiple']);
    Route::post('/categories/createMultiple', [CategoryController::class, 'createMultiple']);
    Route::post('/products/createMultiple', [ProductController::class, 'createMultiple']);
});



//**************************************
//  UPDATE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/update/{id?}', [BrandController::class, 'update']);

    // Route::post('/colors/update/{id?}', [ColorController::class, 'update']);

    Route::post('/shapes/update/{id?}', [ShapeController::class, 'update']);

    Route::post('/materials/update/{id?}', [MaterialController::class, 'update']);

    Route::post('/features/update/{id?}', [FeatureController::class, 'update']);

    Route::post('/categories/update/{id?}', [CategoryController::class, 'update']);

    Route::post('/branches/update/{id?}', [BranchController::class, 'update']);

    Route::post('/products/update/{id?}', [ProductController::class, 'update']);

    Route::post('/product-images/update/{id?}', [ProductImageController::class, 'update']);

    Route::post('/product-features/update/{id?}', [ProductFeatureController::class, 'update']);

    Route::post('/products/update-each/{id?}/{attributeOfProduct?}', [ProductController::class, 'updateEach']);

    Route::post('/product-details/createForAllBranch', [ProductDetailController::class, 'storeForAllBranch']);
});



//**************************************
// UPDATE PRODUCT DETAIL
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager', 'checkTwoIdsParameter'],
], function () {
    Route::post('/product-details/update/{id1?}/{id2?}/{color?}', [ProductDetailController::class, 'update']);
});



//**************************************
//  GET BY ID
//**************************************
Route::group([
    'middleware' => ['checkIdParameter'],
], function () {
    Route::get('/brands/getById/{id?}', [BrandController::class, 'getById']);
    // Route::get('/colors/getById/{id?}', [ColorController::class, 'getById']);
    Route::get('/shapes/getById/{id?}', [ShapeController::class, 'getById']);
    Route::get('/materials/getById/{id?}', [MaterialController::class, 'getById']);
    Route::get('/features/getById/{id?}', [FeatureController::class, 'getById']);
    Route::get('/categories/getById/{id?}', [CategoryController::class, 'getById']);
    Route::get('/branches/getById/{id?}', [BranchController::class, 'getById']);
    Route::get('/products/getById/{id?}', [ProductController::class, 'getById']);
    Route::get('/product-images/getById/{id?}', [ProductImageController::class, 'getById']);
    Route::get('/product-features/getById/{id?}', [ProductFeatureController::class, 'getById']);

    Route::get('/product-images/getByProductId/{id?}', [ProductImageController::class, 'getByProductId']);

    Route::get('/product-features/getByProductId/{id?}', [ProductFeatureController::class, 'getByProductId']);

    Route::get('/product-details/getByProductId/{id?}', [ProductDetailController::class, 'getByProductId']);

    Route::get('/product-details/getByBranchId/{id?}', [ProductDetailController::class, 'getByBranchId']);
});

//**************************************
//  GET BY PRODUCT AND BRANCH ID
//**************************************
Route::group([
    'middleware' => ['checkTwoIdsParameter'],
], function () {
    Route::get('/product-details/getByProductAndBranchId/{id1?}/{id2?}', [ProductDetailController::class, 'getByProductAndBranchId']);
});



//**************************************
//  SWITCH STATUS
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/brands/switch-status/{id?}', [BrandController::class, 'switchStatus']);

    // Route::post('/colors/switch-status/{id?}', [ColorController::class, 'switchStatus']);

    Route::post('/shapes/switch-status/{id?}', [ShapeController::class, 'switchStatus']);

    Route::post('/materials/switch-status/{id?}', [MaterialController::class, 'switchStatus']);

    Route::post('/features/switch-status/{id?}', [FeatureController::class, 'switchStatus']);

    Route::post('/categories/switch-status/{id?}', [CategoryController::class, 'switchStatus']);

    Route::post('/branches/switch-status/{id?}', [BranchController::class, 'switchStatus']);

    Route::post('/products/switch-status/{id?}', [ProductController::class, 'switchStatus']);
});

//**************************************
//  DELETE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/product-images/delete/{id?}', [ProductImageController::class, 'delete']);

    Route::post('/product-features/delete/{id?}', [ProductImageController::class, 'delete']);
});

//**************************************
//  FILTER
//**************************************
Route::get('/products/filter-frames', [ProductController::class, 'filterFrames']);
Route::get('/products/filter-lenses', [ProductController::class, 'filterLenses']);


