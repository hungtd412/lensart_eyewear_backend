<?php

namespace App\Services;

use App\Repositories\PayOSTransRepositoryInterface;

class PayOSTransService {
    protected $payOSTransRepository;

    public function __construct(PayOSTransRepositoryInterface $payOSTransRepository) {
        $this->payOSTransRepository = $payOSTransRepository;
    }

    public function store($data) {
        $payOSTrans = $this->payOSTransRepository->store($data);

        return response()->json([
            'status' => 'success',
            'payOSTrans' => $payOSTrans
        ], 200);
    }

    public function getAll() {
        $payOSTranses = $this->payOSTransRepository->getAll();

        return response()->json([
            'status' => 'success',
            'payOSTranses' => $payOSTranses
        ], 200);
    }

    public function getByOrderCode($orderCode) {
        $payOSTrans = $this->payOSTransRepository->getByOrderCode($orderCode);

        if ($payOSTrans === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'payOSTrans' => $payOSTrans,
        ], 200);
    }

    public function getByOrderId($orderId) {
        $payOSTrans = $this->payOSTransRepository->getByOrderId($orderId);

        if ($payOSTrans === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'payOSTrans' => $payOSTrans,
        ], 200);
    }

    public function refresh($checkOutService, $payOS) {
        $payOSTranses = $this->payOSTransRepository->getAllUnpaid();

        foreach ($payOSTranses as $payOSTrans) {
            $transInfo = $checkOutService->getPaymentLinkInfoOfOrder($payOSTrans->orderCode, $payOS)->getData()->data;

            if ($transInfo->status === "CANCELLED") {
                $this->payOSTransRepository->delete($transInfo->orderCode);
            } else if ($transInfo->status === "PAID") {
                $payOSTrans = $this->payOSTransRepository->getByOrderCode($transInfo->orderCode);

                $this->payOSTransRepository->updateAmount($payOSTrans, $transInfo->amountPaid);
                //update payment status of order -> trigger update payos table
            }
        }
    }

    public function delete($orderCode) {
        return $this->payOSTransRepository->delete($orderCode);
    }
}
