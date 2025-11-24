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
        // if ($this->orderService->canCheckout($orderId) == false) {
        //     return response()->json([
        //         "error" => 0,
        //         "message" => "Bạn không thể thanh toán đơn hàng này!",
        //     ]);
        // }

        if ($this->orderService->isPaid($orderId) == true) {
            return response()->json([
                "error" => 0,
                "message" => "Đơn hàng đã được thanh toán!",
            ]);
        }

        $body = $request->validated();

        $shipping_fee = $body['shipping_fee'];

        $total_price = intval($this->orderService->getPriceByOrderId($orderId)) + $shipping_fee;
        if ($total_price == 0) {
            return response()->json([
                "error" => 0,
                "message" => "Đơn hàng 0 đồng không cần thanh toán!",
            ]);
        }

        $body['description'] = "Thanh toán đơn hàng " . $orderId;
        $body['amount'] = $total_price;

        $response = $this->checkoutService->createTransaction($body, $this->payOS);
        
        // Check if response is an error response
        $responseData = $response->getData();
        if (isset($responseData->error) && $responseData->error !== 0) {
            return $response;
        }
        
        // Check if response has valid data structure
        // Handle both possible structures from payOS SDK
        $hasValidStructure = false;
        if (isset($responseData->data)) {
            // Check if SDK returns full API response structure
            if (isset($responseData->data->data) && isset($responseData->data->data->orderCode)) {
                $hasValidStructure = true;
            }
            // Check if SDK returns just the data part
            elseif (isset($responseData->data->orderCode)) {
                $hasValidStructure = true;
            }
        }
        
        if (!$hasValidStructure) {
            return response()->json([
                "error" => 1,
                "message" => "Invalid response from payment service: missing orderCode",
                "data" => null
            ], 500);
        }
        
        $data = $this->prepareDataForStoreTransaction($response, $orderId);
        $this->payOSTransService->store($data);

        return $response;
    }

    public function prepareDataForStoreTransaction($response, $orderId) {
        $responseData = $response->getData();
        
        // Handle different response structures from payOS SDK
        // Case 1: SDK returns full API response: {code, desc, data: {orderCode, ...}, signature}
        // Our wrapper: {error: 0, message: "Success", data: {code, desc, data: {orderCode, ...}, signature}}
        // So: responseData->data->data->orderCode
        $orderCode = null;
        
        if (isset($responseData->data->data->orderCode)) {
            // Full API response structure
            $orderCode = $responseData->data->data->orderCode;
        } elseif (isset($responseData->data->orderCode)) {
            // SDK returns just the data part: {orderCode, checkoutUrl, ...}
            // Our wrapper: {error: 0, message: "Success", data: {orderCode, ...}}
            // So: responseData->data->orderCode
            $orderCode = $responseData->data->orderCode;
        }
        
        return [
            'orderCode' => $orderCode,
            'order_id' => $orderId,
            'payment_method' => 'Napas 247',
            'amount' => 0
            //0 is default value, if customer pay successfully, manager or admin refresh transaction, then amount will be equal to amount of money customer already paid
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
