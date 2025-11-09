<?php

use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

//**************************************
// TOKEN APIs
//**************************************

Route::group([
    'prefix' => 'token'
], function () {
    // Public endpoints
    Route::get('/balance', [TokenController::class, 'getBalance']);
    Route::get('/allowance', [TokenController::class, 'getAllowance']);
    Route::post('/prepare-transfer', [TokenController::class, 'prepareTransfer']);
    Route::get('/contract', [TokenController::class, 'getContractInfo']);
});

