<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


//**************************************
// CUSTOMER AUTH
//**************************************
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
});



//**************************************
// ADMIN AUTH
//**************************************
Route::group([
    'prefix' => 'auth/admin'
], function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('customGuest');

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});



//**************************************
// RESET PASSWORD
//**************************************
Route::group([
    'prefix' => 'reset-password'
], function () {
    Route::post('/email', [ForgetPasswordController::class, 'sendResetEmailLink']);

    Route::post('/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
});



//**************************************
// OTP
//**************************************
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/verify-otp', [OTPController::class, 'verifyOtp']);
    Route::post('/resend-otp', [OTPController::class, 'sendMailWithOTP']);
});
