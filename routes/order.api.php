<?php

use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

////////////////////// ADMIN ///////////////////////////////////////


//**************************************
// GET ALL
//**************************************
Route::get('orders', [OrderController::class, 'index'])->middleware(['auth:sanctum', 'can:is-admin-manager']);



//**************************************
//  UPDATE
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin'],
], function () {
    Route::post('/orders/update/{id?}', [OrderController::class, 'update']);
});



//**************************************
//  GET BY ID
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum'],
], function () {
    Route::get('/orders/getById/{id?}', [OrderController::class, 'getById']);
});



//**************************************
//  CHANGE STATUS
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum', 'can:is-admin-manager'],
], function () {
    Route::post('/orders/change-order-status/{id?}', [OrderController::class, 'changeOrderStatus']);

    Route::post('/orders/change-payment-status/{id?}', [OrderController::class, 'changePaymentStatus']);

    Route::post('/orders/switch-status/{id?}', [OrderController::class, 'switchStatus']);
});




//**************************************
//  GET BY STATUS
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager'],
], function () {
    Route::get('/orders/{status?}/{branchId?}', [OrderController::class, 'getByStatusAndBranch']);
});



//**************************************
//  CANCEL ORDER
//**************************************
Route::group([
    'middleware' => ['checkIdParameter', 'auth:sanctum'],
], function () {
    Route::post('/orders/cancel/{id?}', [OrderController::class, 'cancel']);
});



//**************************************
//  MOMO
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/momo_payment/atm', [CheckOutController::class, 'momoATMPayment']);
    Route::post('/momo_payment/qr', [CheckOutController::class, 'momoQRPayment']);
});

//**************************************
//  DASHBOARD
//**************************************

Route::group([
    'middleware' => ['auth:sanctum', 'can:is-admin-manager']
], function () {
    Route::get('/dashboard/todays-orders', [DashboardController::class, 'getTodaysOrders']);
    Route::get('/dashboard/revenue', [DashboardController::class, 'getRevenue']);
    Route::get('/dashboard/completed-orders', [DashboardController::class, 'getCompletedOrders']);
    Route::get('/dashboard/pending-orders', [DashboardController::class, 'getPendingOrders']);
    Route::get('/dashboard/cancelled-orders', [DashboardController::class, 'getCancelledOrders']);
    Route::get('/dashboard/products_sold', [DashboardController::class, 'getProductsSold']);
    Route::get('/dashboard/stock', [DashboardController::class, 'getStock']);
    Route::get('/dashboard/new_customers', [DashboardController::class, 'getNewCustomers']);
    Route::get('/dashboard/average-order-value', [DashboardController::class, 'getAverageOrderValue']);
});


////////////////////// CUSTOMER ///////////////////////////////////////

//**************************************
//  GET ORDERS BY CUSTOMER
//**************************************
Route::get('/customer/orders', [OrderController::class, 'getCustomerOrder'])->middleware(['auth:sanctum', 'can:is-customer']);

//**************************************
// CREATE
//**************************************
Route::group([
    'middleware' => ['auth:sanctum', 'can:is-customer'],
], function () {
    Route::post('/orders/create', [OrderController::class, 'store']);
});
