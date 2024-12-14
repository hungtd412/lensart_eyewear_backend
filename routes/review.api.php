<?php

use App\Http\Controllers\ProductReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/reviews/getAllActive', [ProductReviewController::class, 'getAllActive']);

Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager'],
], function () {
    Route::get('/reviews', [ProductReviewController::class, 'index']);
    Route::post('/reviews/create', [ProductReviewController::class, 'store']);
    Route::post('/reviews/update/{id?}', [ProductReviewController::class, 'update']);
    Route::post('/reviews/switch-status/{id?}', [ProductReviewController::class, 'switchStatus']);
    Route::post('/reviews/delete/{id?}', [ProductReviewController::class, 'delete']);
});

Route::group([
    'middleware' => ['checkIdParameter'],
], function () {
    Route::get('/reviews/getById/{id?}', [ProductReviewController::class, 'getById']);
});
