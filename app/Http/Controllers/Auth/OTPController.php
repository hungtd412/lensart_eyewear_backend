<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOTPRequest;
use App\Models\Otp;
use App\Models\User;
use App\Services\OTPService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OTPController extends Controller {
    protected $otpService;

    public function __construct(OTPService $otpService) {
        $this->otpService = $otpService;
    }

    public function verifyOtp(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|digits:6',
        ]);

        $otpRecord = Otp::where('user_id', $request->user_id)->first();

        if (!$otpRecord || (int)$otpRecord->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return response()->json(['message' => 'OTP has expired'], 400);
        }

        // Update user status to active
        $user = User::find($request->user_id);
        $user->status = 'active';
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Delete the OTP record
        $otpRecord->delete();

        return response()->json(['message' => 'User activated successfully'], 200);
    }

    public function sendMailWithOTP(SendOTPRequest $request) {
        $this->otpService->sendMailWithOTP($request->userId, $request->email);

        return response()->json(['message' => 'User registered. OTP sent to email.'], 201);
    }
}
