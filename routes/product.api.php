<?php

use App\Http\Controllers\BlogController;
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

// ADMIN & MANAGER (CUSTOMER Ở DƯỚI)

//**************************************
// GET ALL - Admin routes (require authentication)
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager'],
], function () {
Route::get('/brands', [BrandController::class, 'index']);
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
});



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
    //need to pass: name, status
    Route::post('/brands/update/{id?}', [BrandController::class, 'update']);

    // Route::post('/colors/update/{id?}', [ColorController::class, 'update']);

    //need to pass: name, status
    Route::post('/shapes/update/{id?}', [ShapeController::class, 'update']);

    //need to pass: name, status
    Route::post('/materials/update/{id?}', [MaterialController::class, 'update']);

    //need to pass: name, status
    Route::post('/features/update/{id?}', [FeatureController::class, 'update']);

    //need to pass: name, status
    Route::post('/categories/update/{id?}', [CategoryController::class, 'update']);

    //need to pass: address, manager_id, index, status
    Route::post('/branches/update/{id?}', [BranchController::class, 'update']);

    //need to pass: name, description brand_id category_id
    // color, shape_id material_id gender, array of feature ids
    Route::post('/products/update/{id?}', [ProductController::class, 'update']);

    //api nay ko can dung, dùng api xóa ảnh rồi create ảnh mới
    Route::post('/product-images/update/{id?}', [ProductImageController::class, 'update']);

    Route::post('/product-features/update/{id?}', [ProductFeatureController::class, 'update']);

    Route::post('/products/update-each/{id?}/{attributeOfProduct?}', [ProductController::class, 'updateEach']);
});



//**************************************
// UPDATE PRODUCT DETAIL
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager', 'checkTwoIdsParameter'],
], function () {
    // gắn ở url: product_id, branch_id, color,
    // gắn ở body: chỉ cho cập nhật quantity, status
    Route::post('/product-details/update/{id1?}/{id2?}/{color?}', [ProductDetailController::class, 'update']);
});



//**************************************
//  GET BY ID
//**************************************
// only pass id
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
    Route::get('/products/getByCategoryId/{id?}', [ProductController::class, 'getByCategoryId']);
    Route::get('/product-images/getById/{id?}', [ProductImageController::class, 'getById']);
    Route::get('/product-features/getById/{id?}', [ProductFeatureController::class, 'getById']);

    Route::get('/product-images/getByProductId/{id?}', [ProductImageController::class, 'getByProductId']);

    Route::get('/product-features/getByProductId/{id?}', [ProductFeatureController::class, 'getByProductId']);

    Route::get('/product-details/getByProductId/{id?}', [ProductDetailController::class, 'getByProductId']);

    Route::get('/product-details/getByBranchId/{id?}', [ProductDetailController::class, 'getByBranchId']);
});



//**************************************
//  GET PRODUCT DETAILS BY PRODUCT AND BRANCH ID
//**************************************
Route::group([
    'middleware' => ['checkTwoIdsParameter'],
], function () {
    Route::get('/product-details/getByProductAndBranchId/{id1?}/{id2?}', [ProductDetailController::class, 'getByProductAndBranchId']);
});



//**************************************
//  SWITCH STATUS
//**************************************
// only pass id
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

    Route::post('/product-details/switch-status/{id?}', [ProductDetailController::class, 'switchStatus']);
});

//**************************************
//  DELETE
//**************************************
// only pass id
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/product-images/delete/{id?}', [ProductImageController::class, 'delete']);

    Route::post('/product-features/delete/{id?}', [ProductImageController::class, 'delete']);
});

// CUSTOMER
//**************************************
//  GET ACTIVE PRODUCTS FOR CUSTOMER
//**************************************
//**************************************
// GET ALL
//**************************************
Route::get('/active/brands', [BrandController::class, 'indexActive']);
// Route::get('/colors', [ColorController::class, 'index']);
Route::get('/active/shapes', [ShapeController::class, 'indexActive']);
Route::get('/active/materials', [MaterialController::class, 'indexActive']);
Route::get('/active/features', [FeatureController::class, 'indexActive']);
Route::get('/active/categories', [CategoryController::class, 'indexActive']);
Route::get('/active/branches', [BranchController::class, 'indexActive']);
Route::get('/active/products', [ProductController::class, 'indexActive']);
// Route::get('/active/product-features', [ProductFeatureController::class, 'indexActive']); Lấy route trên không cần active
// Route::get('/active/product-images', [ProductImageController::class, 'indexActive']); Lấy route trên không cần active
Route::get('/active/product-details', [ProductDetailController::class, 'indexActive']);


//**************************************
//  GET BY ID
//**************************************
Route::group([
    'middleware' => ['checkIdParameter'],
], function () {
    Route::get('/active/brands/getById/{id?}', [BrandController::class, 'getByIdActive']);
    // Route::get('/colors/getById/{id?}', [ColorController::class, 'getById']);
    Route::get('/active/shapes/getById/{id?}', [ShapeController::class, 'getById']);
    Route::get('/active/materials/getById/{id?}', [MaterialController::class, 'getByIdActive']);
    Route::get('/active/features/getById/{id?}', [FeatureController::class, 'getByIdActive']);
    Route::get('/active/categories/getById/{id?}', [CategoryController::class, 'getByIdActive']);
    Route::get('/active/branches/getById/{id?}', [BranchController::class, 'getByIdActive']);
    Route::get('/active/products/getById/{id?}', [ProductController::class, 'getByIdActive']);
    Route::get('/active/products/getByCategoryId/{id?}', [ProductController::class, 'getByCategoryIdActive']);
    // Route::get('/active/product-images/getById/{id?}', [ProductImageController::class, 'getByIdActive']);
    // Route::get('/active/product-features/getById/{id?}', [ProductFeatureController::class, 'getByIdActive']);

    // Route::get('/active/product-images/getByProductId/{id?}', [ProductImageController::class, 'getByProductIdActive']);

    // Route::get('/active/product-features/getByProductId/{id?}', [ProductFeatureController::class, 'getByProductIdActive']);

    Route::get('/active/product-details/getByProductId/{id?}', [ProductDetailController::class, 'getByProductIdActive']);

    Route::get('/active/product-details/getByBranchId/{id?}', [ProductDetailController::class, 'getByBranchIdActive']);
});

//**************************************
//  GET BY PRODUCT AND BRANCH ID
//**************************************
Route::group([
    'middleware' => ['checkTwoIdsParameter'],
], function () {
    Route::get('/active/product-details/getByProductAndBranchId/{id1?}/{id2?}', [ProductDetailController::class, 'getByProductAndBranchIdActive']);
});



//**************************************
//  SEARCH PRODUCT ON HOMEPAGE
//**************************************
Route::get('/products/search', [ProductController::class, 'searchProduct']);

//**************************************
//  FILTER
//**************************************
Route::get('/products/filter-frames', [ProductController::class, 'filterFrames']);
Route::get('/products/filter-lenses', [ProductController::class, 'filterLenses']);


//**************************************
//  SHOW PRODUCT ON HOMEPAGE
//**************************************
Route::get('/best-selling-products', [ProductController::class, 'getBestSellingProducts']);
Route::get('/newest-products', [ProductController::class, 'getNewestProducts']);
