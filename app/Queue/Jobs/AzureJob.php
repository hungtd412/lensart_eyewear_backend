<?php

namespace App\Queue\Jobs;

use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;
use MicrosoftAzure\Storage\Queue\Models\QueueMessage;

class AzureJob extends Job implements JobContract
{
    /**
     * The Azure Queue client instance.
     *
     * @var \MicrosoftAzure\Storage\Queue\QueueRestProxy
     */
    protected $azure;

    /**
     * The Azure Queue message instance.
     *
     * @var \MicrosoftAzure\Storage\Queue\Models\QueueMessage
     */
    protected $message;

    /**
     * The queue that the job belongs to.
     *
     * @var string
     */
    protected $queue;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \MicrosoftAzure\Storage\Queue\QueueRestProxy  $azure
     * @param  \MicrosoftAzure\Storage\Queue\Models\QueueMessage  $message
     * @param  string  $connectionName
     * @param  string  $queue
     * @return void
     */
    public function __construct(
        Container $container,
        QueueRestProxy $azure,
        QueueMessage $message,
        $connectionName,
        $queue
    ) {
        $this->azure = $azure;
        $this->message = $message;
        $this->queue = $queue;
        $this->connectionName = $connectionName;
        $this->container = $container;
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return (int) $this->message->getDequeueCount();
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->message->getMessageText();
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();

        $this->azure->deleteMessage(
            $this->queue,
            $this->message->getMessageId(),
            $this->message->getPopReceipt()
        );
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int  $delay
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);

        $this->azure->updateMessage(
            $this->queue,
            $this->message->getMessageId(),
            $this->message->getPopReceipt(),
            $this->message->getMessageText(),
            $delay
        );
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->message->getMessageId();
    }

    /**
     * Get the underlying Azure message instance.
     *
     * @return \MicrosoftAzure\Storage\Queue\Models\QueueMessage
     */
    public function getAzureMessage()
    {
        return $this->message;
    }
}

