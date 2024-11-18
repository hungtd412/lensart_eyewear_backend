<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy {
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): Response {
        return $user->id === $order->user_id
            || $user->role_id === 1
            || ($user->role_id === 2 && $order->branch_id === $user->branch->id)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function cancel(User $user, Order $order): Response {
        return $user->id === $order->user_id
            || $user->role_id === 1
            || ($user->role_id === 2 && $order->branch_id === $user->branch->id)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): Response {
        return $user->role_id === 1
            || ($user->role_id === 2 && $order->branch_id === $user->branch->id)
            ? Response::allow()
            : Response::deny();
    }
}
