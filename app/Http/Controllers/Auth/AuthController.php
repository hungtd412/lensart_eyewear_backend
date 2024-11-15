<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Services\UserService;

class AuthController extends Controller {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function store(StoreUserRequest $request) {
        return $this->userService->store($request->validated());
    }

    public function login(LoginUserRequest $request) {
        $routePrefix = $request->route()->getPrefix();

        return $this->userService->login($request->validated(), $routePrefix);
    }

    public function logout() {
        return $this->userService->logout();
    }
}
