<?php

namespace App\Providers;

use App\Repositories\BranchRepositoryInterface;
use App\Repositories\BranchRepository;
use Illuminate\Support\ServiceProvider;

class BranchRepositoryServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        $this->app->bind(BranchRepositoryInterface::class, BranchRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        //
    }
}
