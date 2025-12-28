<?php

namespace App\Queue;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;
use MicrosoftAzure\Storage\Queue\Models\CreateMessageOptions;

class AzureQueue extends Queue implements QueueContract
{
    /**
     * The Azure Queue client instance.
     *
     * @var \MicrosoftAzure\Storage\Queue\QueueRestProxy
     */
    protected $azure;

    /**
     * The name of the default queue.
     *
     * @var string
     */
    protected $default;

    /**
     * Visibility timeout for messages (in seconds).
     *
     * @var int
     */
    protected $visibilityTimeout;

    /**
     * Create a new Azure Queue instance.
     *
     * @param  \MicrosoftAzure\Storage\Queue\QueueRestProxy  $azure
     * @param  string  $default
     * @param  array  $config
     * @return void
     */
    public function __construct(QueueRestProxy $azure, $default, array $config = [])
    {
        $this->azure = $azure;
        $this->default = $default;
        $this->visibilityTimeout = $config['visibility_timeout'] ?? 30;
    }

    /**
     * Get the size of the queue.
     *
     * @param  string|null  $queue
     * @return int
     */
    public function size($queue = null)
    {
        $queue = $this->getQueue($queue);
        
        try {
            $metadata = $this->azure->getQueueMetadata($queue);
            return (int) $metadata->getApproximateMessageCount();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string  $job
     * @param  mixed  $data
     * @param  string|null  $queue
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        return $this->pushRaw($this->createPayload($job, $this->getQueue($queue), $data), $queue);
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string  $payload
     * @param  string|null  $queue
     * @param  array  $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        $queue = $this->getQueue($queue);

        try {
            $this->azure->createMessage($queue, $payload);
            return true;
        } catch (\Exception $e) {
            \Log::error('[Azure Queue] Failed to push message', [
                'queue' => $queue,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  string  $job
     * @param  mixed  $data
     * @param  string|null  $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        $payload = $this->createPayload($job, $this->getQueue($queue), $data);
        $seconds = $this->secondsUntil($delay);

        $queue = $this->getQueue($queue);

        try {
            $options = new CreateMessageOptions();
            $options->setVisibilityTimeoutInSeconds($seconds);
            
            $this->azure->createMessage($queue, $payload, $options);
            return true;
        } catch (\Exception $e) {
            \Log::error('[Azure Queue] Failed to push delayed message', [
                'queue' => $queue,
                'delay' => $seconds,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string|null  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        try {
            $messages = $this->azure->listMessages($queue, [
                'numofmessages' => 1,
                'visibilitytimeout' => $this->visibilityTimeout,
            ]);

            $messages = $messages->getQueueMessages();

            if (count($messages) > 0) {
                return new \App\Queue\Jobs\AzureJob(
                    $this->container,
                    $this->azure,
                    $messages[0],
                    $this->connectionName,
                    $queue
                );
            }
        } catch (\Exception $e) {
            \Log::error('[Azure Queue] Failed to pop message', [
                'queue' => $queue,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Get the queue or return the default.
     *
     * @param  string|null  $queue
     * @return string
     */
    public function getQueue($queue)
    {
        return $queue ?: $this->default;
    }

    /**
     * Get the underlying Azure Queue client instance.
     *
     * @return \MicrosoftAzure\Storage\Queue\QueueRestProxy
     */
    public function getAzure()
    {
        return $this->azure;
    }
}

