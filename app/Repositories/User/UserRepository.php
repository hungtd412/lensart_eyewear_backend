<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Cart;
use App\Models\Wishlist;

class UserRepository implements UserRepositoryInterface {
    public function store(array $user): User {
        $newUser = User::create($user);
        // Kiểm tra nếu tạo user thành công
        if ($newUser) {
            // Tạo một giỏ hàng rỗng cho user vừa đăng ký
            Cart::create([
                'user_id' => $newUser->id,
                'total_price' => 0,
            ]);

            // Tạo một wishlist rỗng cho user vừa đăng ký
            Wishlist::create([
                'user_id' => $newUser->id,
            ]);
        }
        return $newUser;
    }

    public function login(array $user, $routePrefix): bool {
        if (auth()->attempt($user)) {
            $user = auth()->user();

            if (is_null($user->email_verified_at))
                return false;

            if ($user->status == 'inactive')
                return false;

            // manager and admin can only login to admin web
            // customer can only login to selling web
            if (
                ($routePrefix === 'api/auth'
                    && ($user->role_id === 1 || $user->role_id === 2))
                || ($routePrefix === 'api/auth/admin' && $user->role_id === 3)
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

    public function getAll() {
        return User::all();
    }

    public function getById($id) {
        return User::findOrFail($id);
    }

    public function getByRole($type) {
        return User::where('role_id', $type)
            ->get();
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
