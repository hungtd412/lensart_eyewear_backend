<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy {
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response {
        //if want to view admin profile
        if ($model->role_id === 1) {
            return $user->id === $model->id
                || $user->role_id === 1
                ? Response::allow()
                : Response::deny();
        }

        //if want to view manager, customer profile
        return $user->id === $model->id
            || $user->role_id === 1
            || $user->role_id === 2
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response {
        if ($model->role_id === 1) {
            return $user->id === $model->id
                || $user->role_id === 1
                ? Response::allow()
                : Response::deny();
        }

        return $user->id === $model->id
            || $user->role_id == 1
            || $user->role_id === 2
            ? Response::allow()
            : Response::deny();
    }
}
