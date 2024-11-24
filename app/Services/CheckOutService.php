<?php

namespace App\Services;

use PayOS\PayOS;


class CheckOutService {

    public function execPostRequest($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function momoATMPayment() {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";


        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua ATM MoMo";
        $amount = "10000";
        $orderId = time() . "";
        $redirectUrl = "http://127.0.0.1:8000/";
        $ipnUrl = "http://127.0.0.1:8000/";
        $extraData = "";


        $requestId = time() . "";
        $requestType = "payWithATM";
        // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        // dd($signature);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        // dd($result);
        $jsonResult = json_decode($result, true);  // decode json
        return $jsonResult['payUrl'];

        //Just a example, please check more in there
        //using redirect if you are coding with Laravel
        // return redirect()->to($jsonResult['payUrl']);


        //header is only used for pure php mysql
        // header('Location: ' . $jsonResult['payUrl']);
    }

    public function momoQRPayment() {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";


        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = "10000";
        $orderId = time() . "";
        $redirectUrl = "http://127.0.0.1:8000/";
        $ipnUrl = "http://127.0.0.1:8000/";
        $extraData = "";

        $requestId = time() . "";
        $requestType = "captureWallet";
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        return $jsonResult['payUrl'];
        // return redirect()->to($jsonResult['payUrl']);
    }

    public function payOSPayment($order, $payOS) {
        $data = [
            "orderCode" => $order['id'],
            "amount" => $order['total_price'],
            "description" => "khach hang chuyen tien",
            "returnUrl" => "http://127.0.0.1:8000/",
            "cancelUrl" => "http://127.0.0.1:8000/"
        ];

        try {
            $response = $payOS->createPaymentLink($data);
            return redirect($response['checkoutUrl']);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function createPaymentLink($payOS) {
        $data = [
            "orderCode" => intval(substr(strval(microtime(true) * 10000), -6)),
            "amount" => 2000,
            "description" => "Thanh toán đơn hàng",
            "returnUrl" => "http://127.0.0.1:8000/",
            "cancelUrl" => "http://127.0.0.1:8000/"
        ];
        error_log($data['orderCode']);

        try {
            $response = $payOS->createPaymentLink($data);
            return redirect($response['checkoutUrl']);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getPaymentLinkInfoOfOrder(string $id, $payOS) {
        try {
            $response = $payOS->getPaymentLinkInformation($id);
            return response()->json([
                "error" => 0,
                "message" => "Success",
                "data" => $response["data"]
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function cancelPaymentLinkOfOrder(string $id, $payOS) {
        try {
            $response = $payOS->cancelPaymentLink($id);
            return response()->json([
                "error" => 0,
                "message" => "success",
                "data" => $response["data"]
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
