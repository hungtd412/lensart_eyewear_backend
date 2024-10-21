<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return $user->id === $model->id || $user->role_id == 1
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        // return $user->id === $model->id || $user->role_id == 1;
        return $user->id === $model->id || $user->role_id == 1
            ? Response::allow()
            : Response::deny();
    }
}
