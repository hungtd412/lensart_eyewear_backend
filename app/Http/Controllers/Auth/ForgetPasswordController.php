<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgetPasswordController extends Controller
{
    public function sendResetEmailLink(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ? response()->json([
            'message' => 'Email đã được gửi!'
        ], 200) : response()->json([
            'message' => __($status)
        ], 400);
    }
}
