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

    public function getAll() {
        return $this->userService->getAll();
    }

    public function getById($id) {
        return $this->userService->getById($id);
    }

    public function getByRole($type) {
        return $this->userService->getByRole($type);
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
