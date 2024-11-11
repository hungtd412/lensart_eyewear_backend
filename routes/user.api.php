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



//**************************************
//  GET USERS
//**************************************
Route::group([
    'middleware' => ['checkTypeParameter', 'auth:sanctum', 'can:is-admin-manager'],
], function () {
    Route::get('/users/role/{type?}', [UserController::class, 'getByRole']);
});

Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin'],
], function () {
    Route::get('/users', [UserController::class, 'getAll']);
    Route::post('/users/create', [UserController::class, 'store']);
});



//**************************************
//  UPDATE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum'],
], function () {
    Route::post('/users/update/{id?}', [UserController::class, 'update']);
});
