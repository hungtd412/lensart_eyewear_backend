<?php

namespace App\Services;

use App\Repositories\CouponRepositoryInterface;
use App\Repositories\OrderDetailRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\Product\ProductDetailRepositoryInterface;
use Carbon\Carbon;

class OrderService {
    protected $orderRepository;
    protected $couponRepository;
    protected $productDetailRepository;
    protected $orderDetailRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, CouponRepositoryInterface $couponRepository, ProductDetailRepositoryInterface
    $productDetailRepository, OrderDetailRepositoryInterface $orderDetailRepository) {
        $this->orderRepository = $orderRepository;
        $this->couponRepository = $couponRepository;
        $this->productDetailRepository = $productDetailRepository;
        $this->orderDetailRepository = $orderDetailRepository;
    }

    public function store($data) {
        $discountPrice = $this->calculateDiscountPrice($data);

        if (!$this->isEnoughQuantityProduct($data)) {
            return response()->json([
                'status' => 'fail',
                'message' => "The quantity of products ordered exceeds the quantity of available products"
            ], 422);
        }

        $this->prepareForOrderData($data, $discountPrice);

        $order = $this->orderRepository->store($data);

        $this->storeOrderDetail($data, $order->id);

        return response()->json([
            'status' => 'success',
            'order' => $order
        ], 200);
    }

    public function isEnoughQuantityProduct($data) {
        foreach ($data['order_details'] as $orderDetail) {
            if (!$this->productDetailRepository->isEnoughQuantity(
                $orderDetail['product_id'],
                $data['branch_id'],
                $orderDetail['color'],
                $orderDetail['quantity']
            )) {
                return false;
            }
        }
        return true;
    }

    public function calculateDiscountPrice(array $data): float {
        // if $data['coupon'] does not exist, then using the false value
        $discount_price = 0;
        try {
            $discount_price = $data['coupon_id'] ?? false
                ? $this->couponRepository->getById($data['coupon_id'])
                ->getData()->coupon->discount_price
                : 0.0;
        } catch (\Throwable $th) {
            $discount_price = 0;
        }
        return $discount_price;
    }

    public function prepareForOrderData(&$data, $discountPrice) {
        $data['user_id'] = auth()->id();
        $data['date'] = Carbon::parse(now())->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        $data['user_id'] = auth()->id();

        $order_total_price = 0;
        foreach ($data['order_details'] as $orderDetail) {
            $order_total_price += $orderDetail['total_price'];
        }
        $data['total_price'] = $order_total_price - $discountPrice;
    }

    public function storeOrderDetail($data, $orderId) {
        foreach ($data['order_details'] as $orderDetail) {
            $orderDetail['order_id'] = $orderId;
            $this->orderDetailRepository->store($orderDetail);
        }
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
