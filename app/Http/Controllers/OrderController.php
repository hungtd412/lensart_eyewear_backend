<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Services\OrderDetailService;
use App\Services\OrderService;

class OrderController extends Controller {
    protected $orderService;
    protected $orderDetailService;

    public function __construct(OrderService $orderService, OrderDetailService $orderDetailService) {
        $this->orderService = $orderService;
        $this->orderDetailService = $orderDetailService;
    }

    public function store(StoreOrderRequest $request) {
        $data = $request->validated();

        return $this->orderService->store($data);
    }

    public function index() {
        return $this->orderService->getAll();
    }

    public function getById($id) {
        return $this->orderService->getById($id);
    }

    public function update(StoreOrderRequest $request, $id) {
        return $this->orderService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->orderService->switchStatus($id);
    }
}
