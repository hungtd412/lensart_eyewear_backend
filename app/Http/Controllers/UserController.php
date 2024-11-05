<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function show(Request $request) {
        return $this->userService->show($request->id);
    }

    public function profile() {
        return $this->userService->profile();
    }

    public function update(UpdateUserRequest $request, $id) {
        return $this->userService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->userService->switchStatus($id);
    }
}
