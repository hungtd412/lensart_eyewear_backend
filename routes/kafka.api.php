<?php

use App\Http\Controllers\KafkaEventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Kafka Event API Routes
|--------------------------------------------------------------------------
|
| These routes are for sending events to Kafka message broker.
| All routes require authentication and admin/manager permissions.
|
*/

// ============================================
// Public Test Routes (for development)
// Remove these in production!
// ============================================
Route::prefix('kafka')->group(function () {
    // Test Kafka connection - NO AUTH (for development testing)
    Route::get('/test-connection', [KafkaEventController::class, 'testConnection']);
});

// ============================================
// Protected Routes (require authentication)
// ============================================
Route::group([
    'prefix' => 'kafka',
    'middleware' => ['auth:sanctum', 'can:is-admin-manager'],
], function () {

    // Order Events
    Route::post('/events/order-created', [KafkaEventController::class, 'sendOrderCreatedEvent']);
    Route::post('/events/order-updated', [KafkaEventController::class, 'sendOrderUpdatedEvent']);
    Route::post('/events/order-cancelled', [KafkaEventController::class, 'sendOrderCancelledEvent']);
    Route::post('/events/order-status-changed', [KafkaEventController::class, 'sendOrderStatusChangedEvent']);

    // Sales Transactions (Simplified Format)
    // Gửi dữ liệu giao dịch: order_id, product_id, quantity, price, timestamp, customer_id
    // Mỗi product trong order = 1 event riêng
    Route::post('/transactions/sales', [KafkaEventController::class, 'sendSalesTransactions']);

    // Generic event sender
    Route::post('/events/send', [KafkaEventController::class, 'sendGenericEvent']);
});

