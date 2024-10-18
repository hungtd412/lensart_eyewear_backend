<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'customGuest'], function () {
    Route::post('/register', [AuthController::class, 'store']);

    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', [UserController::class, 'profile'])->middleware('auth:sanctum');
