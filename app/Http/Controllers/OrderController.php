<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Services\OrderService;

class OrderController extends Controller {
    protected $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request) {
        // return $request;
        return $this->orderService->store($request->validated());
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
