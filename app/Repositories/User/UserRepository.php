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

    public function login(array $user, $routePrefix): bool {
        if (auth()->attempt($user)) {
            $user = auth()->user();

            // if user is not admin or manager then logout
            if (
                $routePrefix === 'api/auth/admin'
                && $user->role_id !== 1 && $user->role_id !== 2
            ) {
                $this->deleteToken();
                return false;
            }

            return true;
        }
        return false;
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

    public function getById($id) {
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
