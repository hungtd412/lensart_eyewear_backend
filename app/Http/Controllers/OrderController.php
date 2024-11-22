<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Http\Requests\Order\UpdatePaymentStatusRequest;
use App\Services\OrderDetailService;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    // ADMIN
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        return $this->orderService->store($data);
    }

    public function index()
    {
        return $this->orderService->getAll();
    }

    public function getById($id)
    {
        return $this->orderService->getById($id);
    }

    public function getByStatusAndBranch($status, $branchId = null)
    {
        return $this->orderService->getByStatusAndBranch($status, $branchId);
    }

    public function update(StoreOrderRequest $request, $id)
    {
        return $this->orderService->update($request->validated(), $id);
    }

    public function changeOrderStatus(UpdateOrderStatusRequest $request, $id)
    {
        return $this->orderService->changeOrderStatus($id, $request->validated()['order_status']);
    }

    public function changePaymentStatus(UpdatePaymentStatusRequest $request, $id)
    {
        return $this->orderService->changePaymentStatus($id, $request->validated()['payment_status']);
    }

    public function cancel($id)
    {
        return $this->orderService->cancel($id);
    }

    public function switchStatus($id)
    {
        return $this->orderService->switchStatus($id);
    }

    // CUSTOMER
    public function getCustomerOrder()
    {
        return $this->orderService->getCustomerOrder();
    }
}
