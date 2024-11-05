<?php

namespace App\Repositories\User;

interface UserRepositoryInterface {
    public function store(array $user);
    public function login(array $user): bool;
    public function isLoggedIn(): bool;
    public function createToken();
    public function deleteToken();
    public function findById(array $user);
    public function profile();
    public function update(array $data, $user);
    public function switchStatus($user);
}
