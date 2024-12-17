<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserAddressRequest;
use App\Http\Requests\User\UpdateUserPasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function store(StoreUserRequest $request) {
        return $this->userService->store($request->validated());
    }

    public function getAll() {
        return $this->userService->getAll();
    }

    public function getById($id) {
        return $this->userService->getById($id);
    }

    public function getByRole($id) {
        return $this->userService->getByRole($id);
    }

    public function getCustomers() {
        return $this->userService->getCustomers();
    }

    public function getAdminManagers() {
        return $this->userService->getAdminManagers();
    }

    public function profile() {
        return $this->userService->profile();
    }

    public function update(UpdateUserRequest $request, $id) {
        return $this->userService->update($request->validated(), $id);
    }

    public function updateAddress(UpdateUserAddressRequest $request, $id) {
        return $this->userService->update($request->validated(), $id);
    }

    public function updatePassword(UpdateUserPasswordRequest $request, $id) {
        return $this->userService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->userService->switchStatus($id);
    }
}
