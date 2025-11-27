<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kafka\Socket;
use Kafka\Protocol\Encoder;
use Kafka\Protocol\Decoder;
use Exception;

class KafkaService
{
    protected $config;
    protected $enabled;

    public function __construct()
    {
        $this->config = config('kafka');
        $this->enabled = env('KAFKA_ENABLED', false);
    }

    /**
     * Send event to Kafka topic using Socket Protocol
     *
     * @param string $topic
     * @param array $data
     * @param string|null $key
     * @param int $partition
     * @return bool
     */
    protected function sendEvent(string $topic, array $data, ?string $key = null, int $partition = 0): bool
    {
        if (!$this->enabled) {
            Log::info('[KAFKA DISABLED] Would publish message', [
                'topic' => $topic,
                'data' => $data,
                'key' => $key
            ]);
            return true;
        }

        try {
            // Convert data to JSON
            $message = json_encode($data);
            
            // Parse broker address
            $brokers = explode(',', $this->config['brokers']);
            $firstBroker = trim($brokers[0]);
            
            // Parse host and port
            $parts = explode(':', $firstBroker);
            $host = $parts[0] ?? 'localhost';
            $port = (int)($parts[1] ?? 9092);
            
            // Prepare data structure for Kafka Protocol
            $produceData = [
                'required_ack' => $this->config['producer']['required_ack'] ?? 1,
                'timeout' => $this->config['producer']['timeout'] ?? 10000,
                'data' => [
                    [
                        'topic_name' => $topic,
                        'partitions' => [
                            [
                                'partition_id' => $partition,
                                'messages' => [$message],
                            ],
                        ],
                    ],
                ],
            ];
            
            // Create socket connection
            $conn = new Socket($host, $port);
            $conn->connect();
            
            // Encode and send produce request
            $encoder = new Encoder($conn);
            $encoder->produceRequest($produceData);
            
            // Get response if required_ack > 0
            if (($this->config['producer']['required_ack'] ?? 1) > 0) {
                $decoder = new Decoder($conn);
                $result = $decoder->produceResponse();
            }
            
            // Close connection
            $conn->close();
            
            Log::info('[KAFKA] Event sent successfully', [
                'topic' => $topic,
                'partition' => $partition,
                'message_size' => strlen($message),
                'key' => $key
            ]);
            
            return true;

        } catch (Exception $e) {
            Log::error('[KAFKA] Failed to send event', [
                'topic' => $topic,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Publish order created event
     * 
     * @param \App\Models\Order $order
     * @return bool
     */
    public function publishOrderCreated($order): bool
    {
        try {
            $topic = $this->config['topics']['order_created'] ?? 'order-created';
            
            // Load order details
            if (!$order->relationLoaded('orderDetails')) {
                $order->load('orderDetails');
            }

            // Build products array - chỉ những thông tin cần thiết
            $products = $order->orderDetails->map(function($detail) {
                return [
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'price' => $detail->total_price,
                ];
            })->toArray();

            // Build event - chỉ những field cần thiết
            $event = [
                'order_id' => $order->id,
                'timestamp' => $order->date,
                'customer_id' => $order->user_id,
                'products' => $products,
            ];

            // Send event to Kafka
            return $this->sendEvent($topic, $event, "order_{$order->id}");

        } catch (Exception $e) {
            Log::error('[KAFKA] Failed to publish order created event', [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send Sales Transaction Events to Kafka
     * Mỗi sản phẩm trong order = 1 event riêng (OPTIONAL - nếu muốn gửi riêng từng product)
     * 
     * @param \App\Models\Order $order
     * @return array ['success' => int, 'failed' => int, 'total' => int]
     */
    public function sendSalesTransactions($order): array
    {
        $topic = $this->config['topics']['order_created'] ?? 'order-created';
        
        $results = [
            'success' => 0,
            'failed' => 0,
            'total' => 0,
        ];

        // Load order details
        if (!$order->relationLoaded('orderDetails')) {
            $order->load('orderDetails');
        }
        
        $orderDetails = $order->orderDetails;
        
        if ($orderDetails->isEmpty()) {
            Log::warning("Order {$order->id} không có sản phẩm nào");
            return $results;
        }

        // Gửi mỗi product như 1 transaction event riêng
        foreach ($orderDetails as $detail) {
            $results['total']++;
            
            // Transaction event cho từng sản phẩm
            $transaction = [
                'event_type' => 'sales.transaction',
                'order_id' => $order->id,
                'product_id' => $detail->product_id,
                'quantity' => $detail->quantity,
                'price' => $detail->total_price,
                'timestamp' => $order->date,
                'customer_id' => $order->user_id,
            ];

            // Send transaction event
            $sent = $this->sendEvent($topic, $transaction, "order_{$order->id}_product_{$detail->product_id}");
            
            if ($sent) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }
}
