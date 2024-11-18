<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface {
    public function store(array $data): Order {
        return Order::create($data);
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
