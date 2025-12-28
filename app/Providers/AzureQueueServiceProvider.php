<?php

namespace App\Providers;

use App\Queue\Connectors\AzureQueueConnector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;

class AzureQueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Queue::addConnector('azure-queue', function () {
            return new AzureQueueConnector;
        });
    }
}

