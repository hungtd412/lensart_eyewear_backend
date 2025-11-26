<?php

namespace App\Queue\Connectors;

use App\Queue\AzureQueue;
use Illuminate\Queue\Connectors\ConnectorInterface;
use MicrosoftAzure\Storage\Queue\QueueRestProxy;

class AzureQueueConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $connectionString = $config['connection_string'] ?? '';
        $queue = $config['queue'] ?? 'default';

        // Create Azure Queue client
        $queueClient = QueueRestProxy::createQueueService($connectionString);

        return new AzureQueue($queueClient, $queue, $config);
    }
}

