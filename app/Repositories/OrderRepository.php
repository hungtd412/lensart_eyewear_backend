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
        $order = Order::with(['orderDetails', 'user', 'coupon', 'branch'])->find($id);
        return $order;
    }

    public function getPriceByOrderId($orderId) {
        return Order::find($orderId)?->total_price;
    }

    public function getByStatusAndBranch($status, $branchId = null) {
        $orderStatusList = ['Đang xử lý', 'Đã xử lý và sẵn sàng giao hàng', 'Đang giao hàng', 'Đã giao', 'Đã hủy'];
        $paymentStatusList = ['Chưa thanh toán', 'Đã thanh toán'];

        $ordersList = Order::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END");
        if (in_array($status, $orderStatusList)) {
            $ordersList = $ordersList->where('order_status', $status);
        } else if (in_array($status, $paymentStatusList)) {
            $ordersList = $ordersList->where('payment_status', $status);
        }

        if (!is_null($branchId)) {
            $ordersList->where('branch_id', $branchId);
        }

        return $ordersList->get();
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

    // CUSTOMER
    public function getCustomerOrder() {
        return Order::where('user_id', auth()->id())
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->get();
    }
}
