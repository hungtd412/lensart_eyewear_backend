<?php

namespace App\Repositories\Auth\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function store(array $user): User;
}
