<?php

namespace App\Services;

use Carbon\Carbon;
use App\Mail\OTPMail;
use App\Repositories\OTPRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class OTPService {
    protected $otpRepository;

    public function __construct(OTPRepositoryInterface $otpRepository) {
        $this->otpRepository = $otpRepository;
    }

    public function verifyOtp($data) {
        $otpRecord = $this->otpRepository->getByUserId($data['user_id']);

        if (!$otpRecord || (int)$otpRecord->otp !== $data['otp']) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return response()->json(['message' => 'OTP has expired'], 400);
        }

        return null;
    }

    public function prepareDataForStoreOTP($userId) {
        $otp = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);

        return [
            'user_id' => $userId,
            'otp' => $otp,
            'expires_at' => $expiresAt
        ];
    }

    public function generateOtp($userId) {
        $data = $this->prepareDataForStoreOTP($userId);

        $this->otpRepository->storeOrUpdate($data);

        return $data['otp'];
    }

    public function sendMailWithOTP($userId, $email) {
        $otp = $this->generateOtp($userId);

        Mail::to($email)->send(new OTPMail($otp));

        return response()->json([
            'message' => 'User registered. OTP sent to email.',
            'userId' => $userId,
            'email' => $email
        ], 201);
    }

    public function getByUserId($userId) {
        return $this->otpRepository->getByUserId($userId);
    }

    public function deleteByUserId($userId) {
        $otp = $this->otpRepository->getByUserId($userId);

        $this->otpRepository->delete($otp);

        return response()->json([
            'message' => 'success',
        ], 200);
    }
}
