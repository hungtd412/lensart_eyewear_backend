<?php

namespace App\Services;

use App\Repositories\OrderRepositoryInterface;

class OrderService {
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function store($data) {
        $order = $this->orderRepository->store($data);

        return response()->json([
            'status' => 'success',
            'order' => $order
        ], 200);
    }

    public function getAll() {
        $orders = $this->orderRepository->getAll();

        return response()->json([
            'status' => 'success',
            'orders' => $orders
        ], 200);
    }

    public function getById($id) {
        $order = $this->orderRepository->getById($id);

        if ($order === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'order' => $order,
        ], 200);
    }

    public function update($data, $id) {
        $order = $this->orderRepository->getById($id);

        $this->orderRepository->update($data, $order);

        return response()->json([
            'message' => 'success',
            'order' => $order
        ], 200);
    }

    public function switchStatus($id) {
        $order = $this->orderRepository->getById($id);

        $this->orderRepository->switchStatus($order);

        return response()->json([
            'message' => 'success',
            'order' => $order
        ], 200);
    }
}
