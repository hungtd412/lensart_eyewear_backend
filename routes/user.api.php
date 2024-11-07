<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'users'
], function () {
    Route::get('/profile', [UserController::class, 'profile']);

    Route::group([
        'middleware' => ['checkIdParameter', 'auth:sanctum'],
    ], function () {
        Route::get('/getById/{id?}', [UserController::class, 'getById']);
        Route::post('/update/{id?}', [UserController::class, 'update']);
        Route::post('/switch-status/{id?}', [UserController::class, 'switchStatus']);
    });
});
