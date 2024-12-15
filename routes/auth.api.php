<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;


//**************************************
// CUSTOMER AUTH
//**************************************
Route::group([
    'prefix' => 'auth'
], function () {
    //email password firstname lastname phone address
    Route::post('/register', [AuthController::class, 'store'])->middleware('customGuest');

    Route::post('/login', [AuthController::class, 'login'])->middleware('customGuest');

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
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
// ko lam
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
    //pass userId, otp
    //if otp is right, change user status to active, else still inactive
    Route::post('/verify-otp', [OTPController::class, 'verifyOtp']);

    //pass userId, email
    Route::post('/resend-otp', [OTPController::class, 'sendMailWithOTP']);
});
