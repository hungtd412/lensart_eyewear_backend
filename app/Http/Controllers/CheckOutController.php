<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayOSTransactionRequest;
use App\Services\CheckOutService;
use App\Services\OrderService;
use App\Services\PayOSTransService;

class CheckOutController extends Controller {
    protected $checkoutService;
    protected $orderService;
    protected $payOSTransService;

    public function __construct(CheckOutService $checkoutService, OrderService $orderService, PayOSTransService $payOSTransService) {
        $this->checkoutService = $checkoutService;
        $this->orderService = $orderService;
        $this->payOSTransService = $payOSTransService;
        parent::__construct();
    }

    public function momoATMPayment() {
        return $this->checkoutService->momoATMPayment();
    }

    public function momoQRPayment() {
        return $this->checkoutService->momoQRPayment();
    }

    public function createTransaction(StorePayOSTransactionRequest $request, $orderId) {
        if ($this->orderService->isPaid($orderId) == true) {
            return response()->json([
                "error" => 0,
                "message" => "Đơn hàng đã được thanh toán!",
            ]);
        }

        $response = $this->checkoutService->createTransaction($request->validated(), $this->payOS);

        $data = $this->prepareDataForStoreTransaction($response, $orderId);
        $this->payOSTransService->store($data);

        return $response;
    }

    public function prepareDataForStoreTransaction($response, $orderId) {
        return [
            'orderCode' => $response->getData()->data->orderCode,
            'order_id' => $orderId,
            'amount' => 0
        ];
    }

    public function createPaymentLink() {
        return $this->checkoutService->createPaymentLink($this->payOS);
    }

    public function getPaymentLinkInfoOfOrder($orderId) {
        return $this->checkoutService->getPaymentLinkInfoOfOrder($orderId, $this->payOS);
    }

    public function cancelPaymentLinkOfOrder($orderId) {
        $response = $this->checkoutService->cancelPaymentLinkOfOrder($orderId, $this->payOS);
        return $response;
    }
}
