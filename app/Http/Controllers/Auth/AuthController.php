<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Services\OTPService;
use App\Services\UserService;

class AuthController extends Controller {
    protected $userService;
    protected $otpService;

    public function __construct(UserService $userService, OTPService $otpService) {
        $this->userService = $userService;
        $this->otpService = $otpService;
    }

    public function store(StoreUserRequest $request) {
        $user = $this->userService->store($request->validated())->getData()->user;

        return $this->otpService->sendMailWithOTP($user->id, $user->email);
    }

    public function login(LoginUserRequest $request) {
        $routePrefix = $request->route()->getPrefix();

        return $this->userService->login($request->validated(), $routePrefix);
    }

    public function logout() {
        return $this->userService->logout();
    }
}
