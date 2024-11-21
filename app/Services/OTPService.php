<?php

namespace App\Services;

use App\Models\Otp;
use Carbon\Carbon;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;

class OTPService {
    public function generateOtp($userId) {
        $otp = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);

        Otp::updateOrCreate(
            ['user_id' => $userId],
            ['otp' => $otp, 'expires_at' => $expiresAt]
        );

        return $otp;
    }

    public function sendMailWithOTP($userId, $mail) {
        $otp = $this->generateOtp($userId);

        Mail::to($mail)->send(new OTPMail($otp));
    }
}
