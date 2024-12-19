<?php

use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'middleware' => ['auth:sanctum', 'can:is-admin'],
    ],
    function () {
        Route::get('/banner', [BannerController::class, 'get']);
        Route::post('/banner/create', [BannerController::class, 'store']);
        Route::post('/banner/update', [BannerController::class, 'update']);
        Route::post('/banner/switch-status', [BannerController::class, 'switchStatus']);
    }
);
Route::get('/active-banner', [BannerController::class, 'getActive']);
