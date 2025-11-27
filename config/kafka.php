<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Kafka Broker Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration is for connecting to Kafka brokers.
    | Make sure to set these values in your .env file.
    |
    */

    'brokers' => env('KAFKA_BROKERS', 'localhost:9092'),

    /*
    |--------------------------------------------------------------------------
    | Kafka Topic Configuration
    |--------------------------------------------------------------------------
    |
    | Define topics for different event types.
    |
    */

    'topics' => [
        'order_events' => env('KAFKA_ORDER_TOPIC', 'order-events'),
        'order_created' => env('KAFKA_ORDER_CREATED_TOPIC', 'order-created'),
        'order_updated' => env('KAFKA_ORDER_UPDATED_TOPIC', 'order-updated'),
        'order_cancelled' => env('KAFKA_ORDER_CANCELLED_TOPIC', 'order-cancelled'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Kafka Producer Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for Kafka producer.
    |
    */

    'producer' => [
        'timeout' => env('KAFKA_PRODUCER_TIMEOUT', 10000), // 10 seconds
        'is_async' => env('KAFKA_PRODUCER_ASYNC', true),
        'required_ack' => env('KAFKA_REQUIRED_ACK', 1), // 0 = no ack, 1 = leader ack, -1 = all replicas ack
    ],

    /*
    |--------------------------------------------------------------------------
    | Kafka Consumer Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for Kafka consumer.
    |
    */

    'consumer' => [
        'group_id' => env('KAFKA_CONSUMER_GROUP', 'lensart-consumer-group'),
        'timeout' => env('KAFKA_CONSUMER_TIMEOUT', 10000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Connection Options
    |--------------------------------------------------------------------------
    */

    'sasl' => [
        'enable' => env('KAFKA_SASL_ENABLE', false),
        'mechanism' => env('KAFKA_SASL_MECHANISM', 'PLAIN'), // PLAIN, SCRAM-SHA-256, SCRAM-SHA-512
        'username' => env('KAFKA_SASL_USERNAME', ''),
        'password' => env('KAFKA_SASL_PASSWORD', ''),
    ],

    'ssl' => [
        'enable' => env('KAFKA_SSL_ENABLE', false),
        'ca_cert' => env('KAFKA_SSL_CA_CERT', ''),
        'cert' => env('KAFKA_SSL_CERT', ''),
        'key' => env('KAFKA_SSL_KEY', ''),
    ],
];

