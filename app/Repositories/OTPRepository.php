<?php

namespace App\Repositories;

use App\Models\Otp;

class OTPRepository implements OTPRepositoryInterface {
    public function storeOrUpdate(array $data) {
        return Otp::updateOrCreate($data);
    }

    public function getByUserId($userId) {
        return OTP::where('user_id', $userId)->first();
    }

    public function delete($otp) {
        return $otp->delete();
    }
}
