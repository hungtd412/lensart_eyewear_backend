<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface {
    public function store(array $data): Order {
        return Order::create($data);
    }

    public function getAll() {
        return $this->formatResponse(Order::all());
    }

    public function getById($id) {
        $order = Order::with(['orderDetails', 'user', 'coupon', 'branch'])->find($id);
        return $order;
    }

    public function getPriceByOrderId($orderId) {
        return Order::find($orderId)?->total_price;
    }

    public function getByBranchId($branchIds) {
        $orders = Order::whereIn('branch_id', $branchIds)->get();

        return $this->formatResponse($orders);
    }

    public function getByStatusAndBranch($status, $branchIds = null) {
        $orderStatusList = ['Đang xử lý', 'Đã xử lý và sẵn sàng giao hàng', 'Đang giao hàng', 'Đã giao', 'Đã hủy'];
        $paymentStatusList = ['Chưa thanh toán', 'Đã thanh toán'];

        $ordersList = Order::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END");
        if (in_array($status, $orderStatusList)) {
            $ordersList = $ordersList->where('order_status', $status);
        } else if (in_array($status, $paymentStatusList)) {
            $ordersList = $ordersList->where('payment_status', $status);
        }

        if (!is_null($branchIds)) {
            $ordersList->whereIn('branch_id', $branchIds);
        }

        return $this->formatResponse($ordersList->get());
    }

    public function update(array $data, $order) {
        $order->update($data);
    }

    public function changeOrderStatus($id, $newOrderStatus) {
        $order = $this->getById($id);
        $order->order_status = $newOrderStatus;
        $order->save();
    }

    public function changePaymentStatus($id, $newPaymentStatus) {
        $order = $this->getById($id);
        $order->payment_status = $newPaymentStatus;
        $order->save();
    }

    public function cancel($id) {
        $order = $this->getById($id);
        $order->order_status = 'Đã hủy';
        $order->save();
    }

    public function switchStatus($order) {
        $order->status = $order->status == 'active' ? 'inactive' : 'active';
        $order->save();
    }

    public function formatResponse($orders) {
        return $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'customer_name' => $order->user->firstname . ' ' . $order->user->lastname,
                'date' => $order->date,
                'amount' => $order->total_price,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
            ];
        });
    }

    // CUSTOMER
    public function getCustomerOrder() {
        return Order::where('user_id', auth()->id())
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->get();
    }
}
