<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

class ProductPolicy {
    /**
     * Determine whether the Category can update the model.
     */
    public function update(): Response {
        return auth()->user()->role_id === 1 || auth()->user()->role_id === 2
            ? Response::allow()
            : Response::deny();
    }
}
