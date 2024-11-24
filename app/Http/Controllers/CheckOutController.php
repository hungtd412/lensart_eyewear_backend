<?php

namespace App\Http\Controllers;

use App\Services\CheckOutService;
use App\Services\OrderService;

class CheckOutController extends Controller {
    protected $checkoutService;
    protected $orderService;

    public function __construct(CheckOutService $checkoutService, OrderService $orderService) {
        $this->checkoutService = $checkoutService;
        $this->orderService = $orderService;
        parent::__construct();
    }

    public function momoATMPayment() {
        return $this->checkoutService->momoATMPayment();
    }

    public function momoQRPayment() {
        return $this->checkoutService->momoQRPayment();
    }

    public function payOS($orderId) {
        $order = $this->orderService->getById($orderId);
        return $this->checkoutService->payOSPayment($order, $this->payOS);
    }

    public function createPaymentLink() {
        return $this->checkoutService->createPaymentLink($this->payOS);
    }

    public function getPaymentLinkInfoOfOrder($orderId) {
        return $this->checkoutService->getPaymentLinkInfoOfOrder($orderId, $this->payOS);
    }

    public function cancelPaymentLinkOfOrder($orderId) {
        return $this->checkoutService->cancelPaymentLinkOfOrder($orderId, $this->payOS);
    }
}
