<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendToKafkaQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The order message data
     *
     * @var array
     */
    public $orderMessage;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Create a new job instance.
     *
     * @param array $orderMessage
     */
    public function __construct(array $orderMessage)
    {
        $this->orderMessage = $orderMessage;
        
        // Set connection and queue using Queueable trait methods
        $this->onConnection('azure-queue');
        $this->onQueue('kafka-messages');
    }

    /**
     * Execute the job.
     *
     * This job will be processed by Azure Function
     * Azure Function will then send to Kafka via Ngrok
     *
     * @return void
     */
    public function handle(): void
    {
        // Laravel will automatically push this to Azure Queue
        // The orderMessage will be serialized and sent to Azure Queue Storage
        // Azure Function will be triggered and process this message
        
        Log::info('[Azure Queue] Message prepared for Azure Function', [
            'order_id' => $this->orderMessage['order_id'] ?? null,
            'products_count' => count($this->orderMessage['products'] ?? []),
            'timestamp' => $this->orderMessage['timestamp'] ?? null,
        ]);

        // No need to do anything here
        // Laravel Queue worker will push to Azure Queue automatically
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[Azure Queue] Job failed after all retries', [
            'order_id' => $this->orderMessage['order_id'] ?? null,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // You can send notification, alert, etc.
        // Or fallback to direct Kafka here if needed
    }
}

