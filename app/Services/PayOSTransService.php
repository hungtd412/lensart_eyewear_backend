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
            'data' => $payOSTrans
        ], 200);
    }

    public function getAll() {

        if (auth()->user()->role_id === 1) {
            $payOSTranses = $this->payOSTransRepository->getAll();
        } else if (auth()->user()->role_id === 2) {
            $payOSTranses = $this->payOSTransRepository->getByBranch(
                $this->getAllBranchIdOfManager(auth()->user())
            );
        }

        return response()->json([
            'status' => 'success',
            'data' => $payOSTranses
        ], 200);
    }

    public function getAllBranchIdOfManager($manager) {
        $branchIds = [];
        $branches = $manager->branches;
        foreach ($branches as $branch) {
            $branchIds[] = $branch->id; // Add each branch ID to the array
        }
        return $branchIds;
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
            'data' => $payOSTrans,
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
            'data' => $payOSTrans,
        ], 200);
    }

    public function update($checkOutService, $payOS, $orderId) {
        $payOSTranses = $this->payOSTransRepository->getByOrderId($orderId);

        foreach ($payOSTranses as $payOSTrans) {
            $transInfo = $checkOutService->getPaymentLinkInfoOfOrder($payOSTrans->orderCode, $payOS)->getData()->data;

            if ($transInfo->status === "CANCELLED") {
                $this->payOSTransRepository->delete($transInfo->orderCode);
            } else if ($transInfo->status === "PAID") {
                $payOSTrans = $this->payOSTransRepository->getByOrderCode($transInfo->orderCode);

                $this->payOSTransRepository->updateAmount($payOSTrans, $transInfo->amountPaid);
            }
        }
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
            }
        }
    }

    public function delete($orderCode) {
        return $this->payOSTransRepository->delete($orderCode);
    }
}
