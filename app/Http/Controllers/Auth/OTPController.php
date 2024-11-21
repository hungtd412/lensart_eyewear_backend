<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOTPRequest;
use App\Http\Requests\VerifyOTPRequest;
use App\Services\OTPService;
use App\Services\UserService;

class OTPController extends Controller {
    protected $otpService;
    protected $userService;

    public function __construct(OTPService $otpService, UserService $userService) {
        $this->otpService = $otpService;
        $this->userService = $userService;
    }

    public function verifyOtp(VerifyOTPRequest $request) {
        $message = $this->otpService->verifyOtp($request->validated());
        if (!is_null($message)) {
            return $message;
        }

        $this->updateMailVerify($request->user_id);

        $this->otpService->deleteByUserId($request->user_id);

        return response()->json(['message' => 'User activated successfully'], 200);
    }

    public function updateMailVerify($userId) {
        $this->userService->setEmailVerified($userId);
    }

    public function sendMailWithOTP(SendOTPRequest $request) {
        return $this->otpService->sendMailWithOTP($request->userId, $request->email);
    }
}
