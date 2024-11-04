<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\Auth\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store(array $user): User
    {
        return $this->user->create($user);
    }
}
