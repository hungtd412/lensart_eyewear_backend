<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|confirmed|min:6',
            'email' => 'required|string|email|max:255'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60)
                ])->save();
            }
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Mật khẩu đã được cập nhật thành công!'
        ], 200);
    }
}
