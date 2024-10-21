<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------
|Auth Route
|--------------------------------
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/register', [AuthController::class, 'store'])->middleware('customGuest');

    Route::post('/login', [AuthController::class, 'login'])->middleware('customGuest');

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group([
    'prefix' => 'reset-password'
], function () {
    Route::post('/email', [ForgetPasswordController::class, 'sendResetEmailLink']);

    Route::post('/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
});