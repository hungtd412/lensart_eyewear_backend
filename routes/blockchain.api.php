<?php

use App\Http\Controllers\BlockchainController;
use Illuminate\Support\Facades\Route;

//**************************************
// BLOCKCHAIN / CONTRACT APIs
//**************************************

// Public endpoints - không cần auth
Route::group([
    'prefix' => 'blockchain'
], function () {
    // Lấy thông tin tất cả contracts
    Route::get('/contracts', [BlockchainController::class, 'getContractInfo']);
    
    // Lấy thông tin contract cụ thể
    Route::get('/contracts/{contractName}', [BlockchainController::class, 'getContract']);
    
    // Lấy danh sách networks available
    Route::get('/networks', [BlockchainController::class, 'getAvailableNetworks']);
    
    // Verify wallet address
    Route::post('/verify-address', [BlockchainController::class, 'verifyAddress']);
    
    // Request LENS tokens (faucet)
    Route::post('/faucet/lens', [BlockchainController::class, 'requestLensTokens']);
    
    // Check LENS token balance
    Route::get('/balance/lens', [BlockchainController::class, 'checkLensBalance']);
});

