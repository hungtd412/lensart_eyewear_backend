<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    public function RegisterGate()
    {
        Gate::define('is-admin', function (User $user): bool {
            return $user->role_id === 1;
        });

        Gate::define('is-admin-manager', function (User $user): bool {
            return $user->role_id === 1 || $user->role_id === 2;
        });
        Gate::define('is-customer', function (User $user): bool {
            return $user->role_id === 3;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->RegisterGate();
    }
}
