<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Tài khoản được tạo thành công',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|min:6|max:20',
                'password' => 'required|string|min:6'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        if (auth()->attempt($validated)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công.',
                'token' => auth()->user()->createToken('token')->plainTextToken,
            ], 200);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác.'
        ], 401);
    }

    public function logout(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Bạn đã đăng xuất rồi.'
                ], 400);
            }

            $request->user()->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng xuất thành công.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                // 'message' => 'Đã xảy ra lỗi khi đăng xuất'
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
