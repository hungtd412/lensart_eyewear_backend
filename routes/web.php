<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class);

Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});
