<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        /*'username',
        'password',
        'address',
        'role_id',
        'avatar',
        'phone',
        'address',
        'status' */
        $validated = $request->validate([
            'username' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }
}
