<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    // protected $user;

    // public function __construct(User $user) {
    //     $this->user = $user;
    // }

    public function store(array $user): User {
        return User::create($user);
    }

    public function login(array $user): bool {
        return auth()->attempt($user);
    }

    public function isLoggedIn(): bool {
        return auth()->check();
    }

    public function createToken() {
        return auth()->user()->createToken('token')->plainTextToken;
    }

    public function deleteToken() {
        auth()->user()->tokens()->delete();
    }

    public function findById($id) {
        return User::find($id);
    }

    public function profile() {
        return auth()->user();
    }

    public function update(array $data, $user) {
        $user->update($data);
    }

    public function switchStatus($user) {
        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();
    }
}
