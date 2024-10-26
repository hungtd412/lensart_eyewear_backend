<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // Enable admin to see profile of users
    public function show(Request $request)
    {
        $user = User::find($request->id);

        $response = Gate::inspect("view", $user);

        if ($response->allowed()) {
            return response()->json([
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'message' => 'Bạn không thể xem hồ sơ của người dùng khác!',
            ]);
        }
    }

    public function profile()
    {
        return response()->json([
            'user' => auth()->user(),
        ]);;
    }
}
