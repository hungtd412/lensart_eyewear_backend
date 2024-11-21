<?php

namespace App\Http\Controllers;

use App\Services\CheckOutService;

class CheckOutController extends Controller {
    protected $checkoutService;

    public function __construct(CheckOutService $checkoutService) {
        $this->checkoutService = $checkoutService;
    }

    public function momoATMPayment() {
        return $this->checkoutService->momoATMPayment();
    }

    public function momoQRPayment() {
        return $this->checkoutService->momoQRPayment();
    }
}
