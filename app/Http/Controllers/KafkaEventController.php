<?php

namespace App\Http\Controllers;

use App\Services\KafkaService;
use App\Services\OrderService;
use App\Events\OrderEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\OrderRepositoryInterface;

class KafkaEventController extends Controller {
    protected $kafkaService;
    protected $orderRepository;

    public function __construct(KafkaService $kafkaService, OrderRepositoryInterface $orderRepository) {
        $this->kafkaService = $kafkaService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Send order created event to Kafka
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOrderCreatedEvent(Request $request) {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        try {
            $order = $this->orderRepository->getById($request->order_id);
            
            // Create event
            $orderEvent = new OrderEvent($order, 'order.created');
            $orderData = $orderEvent->toKafkaPayload();

            // Send to Kafka
            $result = $this->kafkaService->sendOrderCreatedEvent($orderData);

            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order created event sent to Kafka successfully',
                    'event_type' => 'order.created',
                    'order_id' => $order->id,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send order created event to Kafka',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending order created event: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send order updated event to Kafka
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOrderUpdatedEvent(Request $request) {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        try {
            $order = $this->orderRepository->getById($request->order_id);
            
            // Create event
            $orderEvent = new OrderEvent($order, 'order.updated');
            $orderData = $orderEvent->toKafkaPayload();

            // Send to Kafka
            $result = $this->kafkaService->sendOrderUpdatedEvent($orderData);

            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order updated event sent to Kafka successfully',
                    'event_type' => 'order.updated',
                    'order_id' => $order->id,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send order updated event to Kafka',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending order updated event: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send order cancelled event to Kafka
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOrderCancelledEvent(Request $request) {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        try {
            $order = $this->orderRepository->getById($request->order_id);
            
            // Create event
            $orderEvent = new OrderEvent($order, 'order.cancelled');
            $orderData = $orderEvent->toKafkaPayload();

            // Send to Kafka
            $result = $this->kafkaService->sendOrderCancelledEvent($orderData);

            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order cancelled event sent to Kafka successfully',
                    'event_type' => 'order.cancelled',
                    'order_id' => $order->id,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send order cancelled event to Kafka',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending order cancelled event: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send order status changed event to Kafka
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOrderStatusChangedEvent(Request $request) {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'old_status' => 'required|string',
            'new_status' => 'required|string',
        ]);

        try {
            $order = $this->orderRepository->getById($request->order_id);
            
            // Create event
            $orderEvent = new OrderEvent($order, 'order.status_changed', [
                'old_status' => $request->old_status,
                'new_status' => $request->new_status,
            ]);
            $orderData = $orderEvent->toKafkaPayload();

            // Send to Kafka
            $result = $this->kafkaService->sendOrderStatusChangedEvent(
                $order->id,
                $request->old_status,
                $request->new_status,
                $orderData
            );

            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order status changed event sent to Kafka successfully',
                    'event_type' => 'order.status_changed',
                    'order_id' => $order->id,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send order status changed event to Kafka',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending order status changed event: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send generic event to Kafka
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendGenericEvent(Request $request) {
        $request->validate([
            'event_type' => 'required|string',
            'data' => 'required|array',
            'topic' => 'nullable|string',
        ]);

        try {
            $result = $this->kafkaService->sendGenericEvent(
                $request->event_type,
                $request->data,
                $request->topic
            );

            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Event sent to Kafka successfully',
                    'event_type' => $request->event_type,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send event to Kafka',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending generic event: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test Kafka connection
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testConnection() {
        try {
            $testData = [
                'test' => true,
                'timestamp' => now()->toIso8601String(),
                'message' => 'This is a test event from LensArt Laravel API',
            ];

            $result = $this->kafkaService->sendGenericEvent('test.connection', $testData);

            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Kafka connection test successful',
                    'kafka_brokers' => config('kafka.brokers'),
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kafka connection test failed',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Kafka connection test failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Kafka connection test failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send Sales Transactions to Kafka
     * Mỗi sản phẩm trong order = 1 event riêng
     * 
     * Format: order_id, product_id, quantity, price, timestamp, customer_id
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSalesTransactions(Request $request) {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        try {
            // Lấy order với order details
            $order = $this->orderRepository->getById($request->order_id);
            
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found',
                ], 404);
            }

            // Gửi sales transactions (1 product = 1 event)
            $results = $this->kafkaService->sendSalesTransactions($order);

            if ($results['success'] > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sales transactions sent to Kafka successfully',
                    'order_id' => $order->id,
                    'results' => $results,
                    'format' => [
                        'order_id' => 'integer',
                        'product_id' => 'integer',
                        'quantity' => 'integer',
                        'price' => 'decimal',
                        'timestamp' => 'ISO8601 string',
                        'customer_id' => 'integer',
                    ],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send sales transactions',
                    'results' => $results,
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending sales transactions: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending transactions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

