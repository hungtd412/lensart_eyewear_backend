<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'user'
], function () {
    Route::get('/show', [UserController::class, 'show']);
    Route::get('/profile', [UserController::class, 'profile']);

    Route::group([
        'middleware' => ['checkIdParameter', 'auth:sanctum'],
    ], function () {
        Route::post('/update/{id?}', [UserController::class, 'update']);
        Route::post('/switch-status/{id?}', [UserController::class, 'switchStatus']);
    });
});
