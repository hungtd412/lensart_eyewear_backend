<?php

namespace App\Repositories;

use App\Models\PayOSTrans;

class PayOSTransRepository implements PayOSTransRepositoryInterface {
    public function store(array $payOSTrans): PayOSTrans {
        return PayOSTrans::create($payOSTrans);
    }

    public function getAll() {
        return PayOSTrans::with('order')->get();
    }

    public function getByBranch($branchIds) {
        return PayOSTrans::whereHas('order', function ($query) use ($branchIds) {
            $query->whereIn('branch_id', $branchIds);
        })->get();
    }

    public function getAllUnpaid() {
        return PayOSTrans::getUnpaidTransactions()->where('amount', '=', 0);
    }

    public function getByOrderCode($orderCode) {
        return PayOSTrans::where('orderCode', $orderCode);
    }

    public function getByOrderId($orderId) {
        return PayOSTrans::where('order_id', $orderId)->get();
    }

    public function updateAmount($payOSTrans, $amount) {
        $payOSTrans->update(['amount' => $amount]);
    }

    public function delete($orderCode) {
        return PayOSTrans::where('orderCode', $orderCode)->delete();
    }
}
