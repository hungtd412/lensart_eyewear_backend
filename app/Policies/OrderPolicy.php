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
            || ($user->role_id === 2 && in_array($order->branch_id, $this->getAllBranchIdOfManager($user)))
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function cancel(User $user, Order $order): Response {
        $branchIds = $user->branches->pluck('id')->toArray();

        return $user->id === $order->user_id
            || $user->role_id === 1
            || ($user->role_id === 2 && in_array($order->branch_id, $this->getAllBranchIdOfManager($user)))
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): Response {
        return $user->role_id === 1
            || ($user->role_id === 2 && in_array($order->branch_id, $this->getAllBranchIdOfManager($user)))
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can checkout the model.
     */
    public function checkout(User $user, Order $order): Response {
        return $user->id === $order->user_id
            ? Response::allow()
            : Response::deny();
    }

    public function getAllBranchIdOfManager($manager) {
        $branchIds = [];
        $branches = $manager->branches;
        foreach ($branches as $branch) {
            $branchIds[] = $branch->id; // Add each branch ID to the array
        }
        return $branchIds;
    }
}
