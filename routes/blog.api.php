<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Public route for end users
Route::get('/active-blogs', [BlogController::class, 'getBlogs']);

// Admin routes - require authentication
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager'],
], function () {
Route::get('/blogs', [BlogController::class, 'index']);
//pass image, title, description
Route::post('/blogs/create', [BlogController::class, 'store']);
//pass image, title, description, status
Route::post('/blogs/update/{id?}', [BlogController::class, 'update']);
Route::post('/blogs/switch-status/{id?}', [BlogController::class, 'switchStatus']);
Route::post('/blogs/delete/{id?}', [BlogController::class, 'delete']);
});

// Public route - get by ID (can be used by both admin and end users)
Route::group([
    'middleware' => ['checkIdParameter'],
], function () {
    Route::get('/blogs/getById/{id?}', [BlogController::class, 'getById']);
});
