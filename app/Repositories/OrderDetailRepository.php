<?php

namespace App\Repositories;

use App\Models\OrderDetail;

class OrderDetailRepository implements OrderDetailRepositoryInterface {
    public function store(array $data): OrderDetail {
        return OrderDetail::create($data);
    }

    public function getAll() {
        return OrderDetail::OrderDetailByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return OrderDetail::find($id);
    }

    public function getByOrderId($orderId) {
        return OrderDetail::where('order_id', $orderId)->get();
    }

    public function update(array $data, $orderDetail) {
        $orderDetail->update($data);
    }

    public function switchStatus($orderDetail) {
        $orderDetail->status = $orderDetail->status == 'active' ? 'inactive' : 'active';
        $orderDetail->save();
    }
}
