<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['customGuest', 'customVerifyCsrfToken']], function () {
    Route::post('/register', [AuthController::class, 'store']);

    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
