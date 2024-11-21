<?php

namespace App\Repositories;

interface OTPRepositoryInterface {
    public function storeOrUpdate(array $otp);
    public function getByUserId($userId);
    public function delete($otp);
}
