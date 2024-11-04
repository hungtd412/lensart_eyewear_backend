<?php

namespace App\Repositories\Auth;

use App\Models\User;

class AuthRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
