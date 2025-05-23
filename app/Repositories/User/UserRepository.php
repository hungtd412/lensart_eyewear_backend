<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Cart;
use App\Models\Wishlist;
use Carbon\Carbon;

class UserRepository implements UserRepositoryInterface {
    public function store(array $user): User {
        $newUser = User::create($user);
        // Kiểm tra nếu tạo user thành công
        if ($newUser) {
            // Tạo một giỏ hàng rỗng cho user vừa đăng ký
            Cart::create([
                'user_id' => $newUser->id,
            ]);

            // Tạo một wishlist rỗng cho user vừa đăng ký
            Wishlist::create([
                'user_id' => $newUser->id,
            ]);
        }
        return $newUser;
    }

    public function login(array $user, $routePrefix): bool {
        if (auth()->attempt(['email' => $user['email'], 'password' => $user['password']])) {
            $user = auth()->user();

            if (is_null($user->email_verified_at) || $user->status == 'inactive') {
                $this->deleteToken();
                return false;
            }

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

    public function getByEmail($email) {
        return User::where('email', $email)
            ->first();
    }

    public function getByRole($id) {
        if ($id == 2) {
            $users = User::where('role_id', $id)->with('branches')->get();
            return $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'role_id' => $user->role_id,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'created_time' => $user->created_at,
                    'status' => $user->status,
                    'branch_id' => $user->branches->pluck('id')->first(), // Assuming branches relationship returns a collection
                ];
            });
        }

        return User::where('role_id', $id)
            ->get();
    }

    public function profile() {
        return auth()->user();
    }

    public function update(array $data, $user) {
        $user->update($data);
    }

    public function setEmailVerified($user) {
        $user->email_verified_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user->save();
    }

    public function switchStatus($user) {
        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();
    }
}
