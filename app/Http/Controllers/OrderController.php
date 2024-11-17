<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Services\CouponService;
use App\Services\OrderDetailService;
use App\Services\OrderService;

class OrderController extends Controller {
    protected $orderService;
    protected $orderDetailService;
    protected $couponService;
    protected $cartService;

    public function __construct(OrderService $orderService, OrderDetailService $orderDetailService, CouponService $couponService) {
        $this->orderService = $orderService;
        $this->orderDetailService = $orderDetailService;
        $this->couponService = $couponService;
    }

    public function store(StoreOrderRequest $request) {
        $data = $request->validated();

        $discountPrice = $this->getDiscountPriceByCouponId($data);

        $order = $this->orderService->store($data, $discountPrice)->getData();
        $this->orderDetailService->store($data, $order->order->id);

        return $order;
    }

    public function getDiscountPriceByCouponId($data) {
        if (array_key_exists('coupon_id', $data)) {
            return $this->couponService->decrementCouponQuantityByOneAndReturn($data['coupon_id'])
                ->getData()->coupon->discount_price;
        }

        return 0;
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
