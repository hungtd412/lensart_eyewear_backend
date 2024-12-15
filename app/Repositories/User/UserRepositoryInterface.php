<?php

namespace App\Repositories\User;

interface UserRepositoryInterface {
    public function store(array $user);
    public function login(array $user, $routePrefix): bool;
    public function isLoggedIn(): bool;
    public function createToken();
    public function deleteToken();
    public function getAll();
    public function getById($id);
    public function getByEmail($email);
    public function getByRole($id);
    public function profile();
    public function update(array $data, $user);
    public function setEmailVerified($user);
    public function switchStatus($user);
}
