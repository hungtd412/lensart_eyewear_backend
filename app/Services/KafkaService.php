<?php

namespace App\Services;

use Kafka\Produce;
use Exception;
use Illuminate\Support\Facades\Log;

class KafkaService {
    protected $config;

    public function __construct() {
        $this->config = config('kafka');
    }

    /**
     * Get Kafka Producer instance
     * 
     * @return \Kafka\Produce
     */
    protected function getProducer() {
        try {
            // Get broker address (without zookeeper for simple produce)
            $brokers = $this->config['brokers'];
            
            // For nmred/kafka-php, we'll use SimpleProduce via Protocol
            // Or if Zookeeper available, use Produce::getInstance
            
            return $brokers;
        } catch (Exception $e) {
            Log::error('Failed to get Kafka producer: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send event to Kafka topic (using low-level protocol)
     *
     * @param string $topic
     * @param array $data
     * @param string|null $key
     * @param int $partition
     * @return bool
     */
    public function sendEvent(string $topic, array $data, ?string $key = null, int $partition = 0): bool {
        try {
            // Convert data to JSON
            $message = json_encode($data);
            
            // Parse broker address
            $brokers = explode(',', $this->config['brokers']);
            $firstBroker = trim($brokers[0]);
            
            // Parse host and port
            $parts = explode(':', $firstBroker);
            $host = $parts[0] ?? 'localhost';
            $port = $parts[1] ?? '9092';
            
            // Prepare data structure for Kafka Protocol
            $produceData = [
                'required_ack' => $this->config['producer']['required_ack'],
                'timeout' => $this->config['producer']['timeout'],
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
            $conn = new \Kafka\Socket($host, $port);
            $conn->connect();
            
            // Encode and send produce request
            $encoder = new \Kafka\Protocol\Encoder($conn);
            $encoder->produceRequest($produceData);
            
            // Get response if required_ack > 0
            if ($this->config['producer']['required_ack'] > 0) {
                $decoder = new \Kafka\Protocol\Decoder($conn);
                $result = $decoder->produceResponse();
            }
            
            // Close connection
            $conn->close();

            Log::info("Event sent to Kafka topic: {$topic}", [
                'partition' => $partition,
                'data_preview' => substr($message, 0, 200),
            ]);

            return true;
        } catch (Exception $e) {
            Log::error("Failed to send event to Kafka: " . $e->getMessage(), [
                'topic' => $topic,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Send Order Created Event
     *
     * @param array $orderData
     * @return bool
     */
    public function sendOrderCreatedEvent(array $orderData): bool {
        $topic = $this->config['topics']['order_created'];
        
        $event = [
            'event_type' => 'order.created',
            'event_id' => uniqid('evt_', true),
            'timestamp' => now()->toIso8601String(),
            'data' => $orderData,
        ];

        return $this->sendEvent($topic, $event, "order_{$orderData['id']}");
    }

    /**
     * Send Order Updated Event
     *
     * @param array $orderData
     * @return bool
     */
    public function sendOrderUpdatedEvent(array $orderData): bool {
        $topic = $this->config['topics']['order_updated'];
        
        $event = [
            'event_type' => 'order.updated',
            'event_id' => uniqid('evt_', true),
            'timestamp' => now()->toIso8601String(),
            'data' => $orderData,
        ];

        return $this->sendEvent($topic, $event, "order_{$orderData['id']}");
    }

    /**
     * Send Order Cancelled Event
     *
     * @param array $orderData
     * @return bool
     */
    public function sendOrderCancelledEvent(array $orderData): bool {
        $topic = $this->config['topics']['order_cancelled'];
        
        $event = [
            'event_type' => 'order.cancelled',
            'event_id' => uniqid('evt_', true),
            'timestamp' => now()->toIso8601String(),
            'data' => $orderData,
        ];

        return $this->sendEvent($topic, $event, "order_{$orderData['id']}");
    }

    /**
     * Send Order Status Changed Event
     *
     * @param int $orderId
     * @param string $oldStatus
     * @param string $newStatus
     * @param array $orderData
     * @return bool
     */
    public function sendOrderStatusChangedEvent(int $orderId, string $oldStatus, string $newStatus, array $orderData): bool {
        $topic = $this->config['topics']['order_events'];
        
        $event = [
            'event_type' => 'order.status_changed',
            'event_id' => uniqid('evt_', true),
            'timestamp' => now()->toIso8601String(),
            'data' => [
                'order_id' => $orderId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'order' => $orderData,
            ],
        ];

        return $this->sendEvent($topic, $event, "order_{$orderId}");
    }

    /**
     * Send Payment Status Changed Event
     *
     * @param int $orderId
     * @param string $oldPaymentStatus
     * @param string $newPaymentStatus
     * @param array $orderData
     * @return bool
     */
    public function sendPaymentStatusChangedEvent(int $orderId, string $oldPaymentStatus, string $newPaymentStatus, array $orderData): bool {
        $topic = $this->config['topics']['order_events'];
        
        $event = [
            'event_type' => 'order.payment_status_changed',
            'event_id' => uniqid('evt_', true),
            'timestamp' => now()->toIso8601String(),
            'data' => [
                'order_id' => $orderId,
                'old_payment_status' => $oldPaymentStatus,
                'new_payment_status' => $newPaymentStatus,
                'order' => $orderData,
            ],
        ];

        return $this->sendEvent($topic, $event, "order_{$orderId}");
    }

    /**
     * Send generic event to Kafka
     *
     * @param string $eventType
     * @param array $data
     * @param string|null $topicOverride
     * @return bool
     */
    public function sendGenericEvent(string $eventType, array $data, ?string $topicOverride = null): bool {
        $topic = $topicOverride ?? $this->config['topics']['order_events'];
        
        $event = [
            'event_type' => $eventType,
            'event_id' => uniqid('evt_', true),
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
        ];

        return $this->sendEvent($topic, $event);
    }
}

