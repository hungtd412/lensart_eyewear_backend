<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface {
    public function store(array $order): Order {
        return Order::create($order);
    }

    public function getAll() {
        return Order::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Order::find($id);
    }

    public function update(array $data, $order) {
        $order->update($data);
    }

    public function switchStatus($order) {
        $order->status = $order->status == 'active' ? 'inactive' : 'active';
        $order->save();
    }
}
