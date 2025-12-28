<?php

namespace App\Services;

use App\Repositories\CouponRepositoryInterface;
use App\Repositories\OrderDetailRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\Product\ProductDetailRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
<<<<<<< Updated upstream
=======
use Illuminate\Support\Facades\Log;
// Azure Queue - optional dependency
// use MicrosoftAzure\Storage\Queue\QueueRestProxy;

>>>>>>> Stashed changes

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
<<<<<<< Updated upstream
=======

    //AZURE
    /**
     * Send order transactions to Azure Queue
     * 
     * @param \App\Models\Order $order
     * @return void
     */
    private function sendToAzureQueue($order)
    {
        try {
            // Check if Azure Queue class exists and connection string is configured
            if (!class_exists('MicrosoftAzure\Storage\Queue\QueueRestProxy')) {
                Log::warning('Azure Queue class not found, skipping queue operation', [
                    'order_id' => $order->id,
                ]);
                return;
            }

            $connectionString = env('AZURE_STORAGE_CONNECTION_STRING');
            if (empty($connectionString)) {
                Log::warning('Azure Storage connection string not configured, skipping queue operation', [
                    'order_id' => $order->id,
                ]);
                return;
            }

            // Build products array
            $products = [];
            
            foreach ($order->orderDetails as $detail) {
                // Use product_id directly from OrderDetail
                $products[] = [
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'price' => number_format($detail->total_price, 2, '.', ''),
                ];
            }

            // Build complete order message
            $orderMessage = [
                'order_id' => $order->id,
                'timestamp' => Carbon::parse($order->date)->format('Y-m-d H:i:s'),
                'customer_id' => $order->user_id,
                'products' => $products,
            ];
            
            // Dispatch single message to Azure Queue
            // \App\Jobs\SendToKafkaQueue::dispatch($orderMessage);

            $queue = \MicrosoftAzure\Storage\Queue\QueueRestProxy::createQueueService($connectionString);

            $queue->createMessage(
                'kafka-messages',
                base64_encode(json_encode($orderMessage))
            );
            
            Log::info('Order queued to Azure', [
                'order_id' => $order->id,
                'products_count' => count($products),
                'message' => $orderMessage,
            ]);

        } catch (\Exception $e) {
            // Log error but don't throw - don't break order creation if queue fails
            Log::error('Failed to queue order to Azure', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            // Don't throw exception - order creation should succeed even if queue fails
            // throw $e;
        }
    }

    /**
     * Fallback: Send directly to Kafka if Azure Queue fails
     * 
     * @param \App\Models\Order $order
     * @return void
     */
    private function fallbackToKafka($order)
    {
        Log::warning('Using Kafka fallback (sync)', [
            'order_id' => $order->id
        ]);

        try {
            // Use existing KafkaService
            $this->kafkaService->publishOrderCreated($order);
            
            Log::info('Order sent to Kafka directly (fallback)', [
                'order_id' => $order->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Kafka fallback also failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
>>>>>>> Stashed changes
}
