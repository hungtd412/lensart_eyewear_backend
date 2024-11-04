<?php

namespace App\Services\Auth;

use App\Repositories\Auth\Interfaces\AuthRepositoryInterface;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function store($data)
    {
        return $this->authRepository->store($data);
    }
}
