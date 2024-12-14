<?php

namespace App\Services;

use App\Repositories\OrderDetailRepositoryInterface;
use Carbon\Carbon;

class OrderDetailService {
    protected $orderDetailRepository;

    public function __construct(OrderDetailRepositoryInterface $orderDetailRepository) {
        $this->orderDetailRepository = $orderDetailRepository;
    }

    public function store($data, $orderId) {
        foreach ($data['order_details'] as $orderDetail) {
            $orderDetail['order_id'] = $orderId;
            $this->orderDetailRepository->store($orderDetail);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function prepareDataForOrderDetail($data) {
        return [
            'data' => $data['order_details']
        ];
    }

    public function getAll() {
        $orderDetails = $this->orderDetailRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $orderDetails
        ], 200);
    }

    public function getById($id) {
        $orderDetail = $this->orderDetailRepository->getById($id);

        if ($orderDetail === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $orderDetail,
        ], 200);
    }

    public function getByOrderId($orderId) {
        $orderDetails = $this->orderDetailRepository->getByOrderId($orderId);

        if ($orderDetails === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $orderDetails,
        ], 200);
    }
}
