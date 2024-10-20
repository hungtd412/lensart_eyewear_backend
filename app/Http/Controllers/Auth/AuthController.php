<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|min:6|max:20|unique:users',
                'password' => 'required|string|min:6',
                'email' => 'required|string|email|max:255|unique:users',
                'address' => 'required|string|max:255',
                'phone' => [
                    'required',
                    'string',
                    'max:11',
                    'regex:/^(0[3|5|7|8|9])[0-9]{8,9}$/'
                ],
            ], [
                'username.required' => 'Tên đăng nhập là bắt buộc.',
                'username.string' => 'Tên đăng nhập phải là một chuỗi.',
                'username.min' => 'Tên đăng nhập phải có ít nhất 6 ký tự.',
                'username.max' => 'Tên đăng nhập không được vượt quá 20 ký tự.',
                'username.unique' => 'Tên đăng nhập này đã được sử dụng.',

                'password.required' => 'Mật khẩu là bắt buộc.',
                'password.string' => 'Mật khẩu phải là một chuỗi.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',

                'email.required' => 'Email là bắt buộc.',
                'email.string' => 'Email phải là một chuỗi.',
                'email.email' => 'Email phải là một địa chỉ email hợp lệ.',
                'email.max' => 'Email không được vượt quá 255 ký tự.',
                'email.unique' => 'Email này đã được sử dụng.',

                'address.required' => 'Địa chỉ là bắt buộc.',
                'address.string' => 'Địa chỉ phải là một chuỗi.',
                'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

                'phone.required' => 'Số điện thoại là bắt buộc.',
                'phone.string' => 'Số điện thoại phải là một chuỗi.',
                'phone.max' => 'Số điện thoại không được vượt quá 11 chữ số.',
                'phone.regex' => 'Số điện thoại phải bắt đầu bằng 0 và theo sau là 9 chữ số.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }


        $user = User::create($validated);

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
            ], [
                'username.required' => 'Tên đăng nhập là bắt buộc.',
                'username.string' => 'Tên đăng nhập phải là một chuỗi.',
                'username.min' => 'Tên đăng nhập phải có ít nhất 6 ký tự.',
                'username.max' => 'Tên đăng nhập không được vượt quá 20 ký tự.',

                'password.required' => 'Mật khẩu là bắt buộc.',
                'password.string' => 'Mật khẩu phải là một chuỗi.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
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
                'token' => auth()->user()->createToken('token')->plainTextToken
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
