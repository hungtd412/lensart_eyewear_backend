<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/active-blogs', [BlogController::class, 'getBlogs']);
Route::get('/blogs', [BlogController::class, 'index']);
//pass image, title, description
Route::post('/blogs/create', [BlogController::class, 'store']);

//pass image, title, description, status
Route::post('/blogs/update/{id?}', [BlogController::class, 'update']);
Route::get('/blogs/getById/{id?}', [BlogController::class, 'getById']);
Route::post('/blogs/switch-status/{id?}', [BlogController::class, 'switchStatus']);
Route::post('/blogs/delete/{id?}', [BlogController::class, 'delete']);
