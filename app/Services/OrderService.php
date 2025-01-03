<?php

namespace App\Services;

use App\Repositories\CouponRepositoryInterface;
use App\Repositories\OrderDetailRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\Product\ProductDetailRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
            'data' => $order
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
                ? $this->couponRepository->getById($data['coupon_id'])->discount_price
                : 0.0;
        } catch (\Throwable $th) {
            $discount_price = 0;
        }
        return $discount_price;
    }

    public function prepareForOrderData(&$data, $discountPrice) {
        $data['date'] = Carbon::parse(now())->setTimeZone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        $data['user_id'] = auth()->id();

        $order_total_price = 0;
        foreach ($data['order_details'] as $orderDetail) {
            $order_total_price += $orderDetail['total_price'];
        }

        $priceAfterDiscount = $order_total_price - $discountPrice;

        if ($priceAfterDiscount < 0) {
            $priceAfterDiscount = 0;
        }

        $data['total_price'] = $priceAfterDiscount;
    }

    public function storeOrderDetail($data, $orderId) {
        foreach ($data['order_details'] as $orderDetail) {
            $orderDetail['order_id'] = $orderId;
            $this->orderDetailRepository->store($orderDetail);
        }
    }

    public function getAll() {
        $currentUser = auth()->user();

        if ($currentUser->role_id === 1) {

            $orders = $this->orderRepository->getAll();
        } else if ($currentUser->role_id === 2) {

            //one manager can manage multiple branches
            $branchIds = $this->getAllBranchIdOfManager($currentUser);

            $orders = $this->orderRepository->getByBranchId($branchIds);
        } else {

            $orders = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    public function getAllBranchIdOfManager($manager) {
        $branchIds = [];
        $branches = $manager->branches;
        foreach ($branches as $branch) {
            $branchIds[] = $branch->id; // Add each branch ID to the array
        }
        return $branchIds;
    }

    public function getById($id) {
        $order = $this->orderRepository->getById($id);
        $response = Gate::inspect("view", $order);

        if ($response->allowed()) {
            return response()->json([
                'data' => $order,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Bạn không thể xem đơn hàng của người dùng này!',
            ], 403);
        }
    }

    public function getPriceByOrderId($orderId) {
        return $this->orderRepository->getPriceByOrderId($orderId);
    }

    public function getByStatus($status) {
        $currentUser = auth()->user();

        if ($currentUser->role_id === 1) {

            $orders = $this->orderRepository->getByStatusAndBranch($status);
        } else if ($currentUser->role_id === 2) {

            //one manager can manage multiple branches
            $branchIds = $this->getAllBranchIdOfManager($currentUser);

            $orders = $this->orderRepository->getByStatusAndBranch($status, $branchIds);
        } else {

            $orders = null;
        }

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);

        // if (!is_null($branchId)) {
        //     if ($this->isValidUser($branchId)) {
        //         return response()->json([
        //             'status' => 'success',
        //             'data' => $this->orderRepository->getByStatusAndBranch($status, $branchId)
        //         ], 200);
        //     } else {
        //         return response()->json([
        //             'status' => 'fail',
        //             'message' => "Bạn không thể xem đơn hàng của chi nhánh khác!"
        //         ], 403);
        //     }
        // }

        // try {
        //     $branchId = auth()->user()->branch->id;
        // } catch (\Throwable $th) {
        //     $branchId = null;
        // }
        // return response()->json([
        //     'data' => $this->orderRepository->getByStatusAndBranch($status, $branchId),
        // ], 200);
    }

    public function isValidUser($branchId) {
        return auth()->user()->role_id === 1
            || (auth()->user()->role_id === 2
                && in_array($branchId, $this->getAllBranchIdOfManager(auth()->user())));
    }

    public function update($data, $id) {
        $order = $this->orderRepository->getById($id);

        $response = Gate::inspect("update", $order);

        if ($response->allowed()) {
            $this->orderRepository->update($data, $order);
            $updatedOrder = $this->orderRepository->getById($id);
            return response()->json([
                'data' => $updatedOrder,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Bạn không thể chỉnh sửa đơn hàng của người dùng này!',
            ], 403);
        }
    }

    public function changeOrderStatus($id, $newOrderStatus) {
        $order = $this->orderRepository->getById($id);

        $response = Gate::inspect("update", $order);

        if ($response->allowed()) {
            $this->orderRepository->changeOrderStatus($id, $newOrderStatus);
            $order = $this->orderRepository->getById($id);
            return response()->json([
                'data' => $order,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Bạn không thể chỉnh sửa đơn hàng của người dùng này!',
            ], 403);
        }
    }

    public function changePaymentStatus($id, $newPaymentStatus) {
        $order = $this->orderRepository->getById($id);

        $response = Gate::inspect("update", $order);

        if ($response->allowed()) {
            $this->orderRepository->changePaymentStatus($id, $newPaymentStatus);
            $order = $this->orderRepository->getById($id);
            return response()->json([
                'data' => $order,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Bạn không thể chỉnh sửa đơn hàng của người dùng này!',
            ], 403);
        }
    }

    public function cancel($id) {
        $order = $this->orderRepository->getById($id);

        $response = Gate::inspect("cancel", $order);

        if ($response->allowed()) {
            if ($order->order_status === 'Đang xử lý') {
                $this->orderRepository->cancel($id);
                $order = $this->orderRepository->getById($id);
                return response()->json([
                    'data' => $order,
                ], 200);
            } else if ($order->order_status === 'Đã hủy') {
                return response()->json([
                    'message' => 'Đơn hàng đã được hủy trước đó rồi!',
                ], 403);
            } else {
                return response()->json([
                    'message' => 'Đơn hàng đã được xử lý sẽ không thể bị hủy!',
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Bạn không thể hủy đơn hàng của người dùng này!',
            ], 403);
        }
    }

    public function switchStatus($id) {
        $order = $this->orderRepository->getById($id);

        if ($this->isValidUser($order->branch_id)) {
            $this->orderRepository->switchStatus($order);
            return response()->json([
                'status' => 'success',
                'data' => $order
            ], 200);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => "Bạn không thể chỉnh sửa đơn hàng của chi nhánh khác!"
            ], 403);
        }
    }

    public function isPaid($orderId): bool {
        return $this->orderRepository->getById($orderId)->payment_status == 'Chưa thanh toán' ? false : true;
    }

    public function canCheckout($orderId) {
        $order = $this->orderRepository->getById($orderId);
        $response = Gate::inspect("checkout", $order);
        if ($response->allowed()) {
            return true;
        } else {
            return false;
        }
    }

    // CUSTOMER
    public function getCustomerOrder() {
        $orders = $this->orderRepository->getCustomerOrder();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }
}
