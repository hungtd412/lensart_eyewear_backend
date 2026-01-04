<?php

namespace App\Services;

use App\Repositories\CartDetailReposityInterface;
use App\Repositories\Product\ProductDetailRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;

class CheckoutService
{
    protected $cartDetailRepository;
    protected $productDetailRepository;
    protected $orderService;

    public function __construct(
        CartDetailReposityInterface $cartDetailRepository,
        ProductDetailRepositoryInterface $productDetailRepository,
        OrderService $orderService
    ) {
        $this->cartDetailRepository = $cartDetailRepository;
        $this->productDetailRepository = $productDetailRepository;
        $this->orderService = $orderService;
    }

    /**
     * BR1: Checkout Entry Rules
     * If user clicks Checkout → Set checkoutSession = initiated
     * Else checkout is not initiated
     */
    public function initiateCheckout(): array
    {
        Session::put('checkoutSession', 'initiated');
        
        return [
            'status' => 'success',
            'checkoutSession' => 'initiated',
        ];
    }

    /**
     * BR2: Review Rules
     * If cart has items → Show items grouped by branch with subtotals
     * Else Set validation result = Failed
     */
    public function reviewCartGroupedByBranch($userId): array
    {
        $cartDetails = $this->cartDetailRepository->getAllCartDetails($userId);

        if ($cartDetails->isEmpty()) {
            return [
                'status' => 'failed',
                'validation_result' => 'Failed',
                'message' => 'Cart is empty',
            ];
        }

        // Group by branch_id
        $groupedByBranch = $cartDetails->groupBy('branch_id');

        $result = [];
        foreach ($groupedByBranch as $branchId => $items) {
            $subtotal = $items->sum('total_price');
            $result[] = [
                'branch_id' => $branchId,
                'branch_name' => $items->first()['branches_name'] ?? 'N/A',
                'items' => $items->toArray(),
                'subtotal' => $subtotal,
            ];
        }

        return [
            'status' => 'success',
            'validation_result' => 'Successful',
            'data' => $result,
        ];
    }

    /**
     * BR3: Shipping Rules
     * If user selects saved address → Set shippingAddress = selected
     * Else Set shippingAddress = new input
     * If required fields missing → Set validation result = Failed
     */
    public function validateShippingAddress(array $shippingData): array
    {
        // Check if using saved address
        if (isset($shippingData['saved_address_id']) && !empty($shippingData['saved_address_id'])) {
            return [
                'status' => 'success',
                'shippingAddress' => [
                    'type' => 'saved',
                    'address_id' => $shippingData['saved_address_id'],
                ],
                'validation_result' => 'Successful',
            ];
        }

        // Validate new input address
        $requiredFields = ['address'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!isset($shippingData[$field]) || empty(trim($shippingData[$field]))) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return [
                'status' => 'failed',
                'validation_result' => 'Failed',
                'message' => 'Required shipping fields are missing',
                'missing_fields' => $missingFields,
            ];
        }

        return [
            'status' => 'success',
            'shippingAddress' => [
                'type' => 'new',
                'address' => $shippingData['address'],
                'note' => $shippingData['note'] ?? null,
            ],
            'validation_result' => 'Successful',
        ];
    }

    /**
     * BR4: Payment Selection Rules
     * If user selects a payment method → Set paymentMethod = selected
     * Else Set validation result = Failed
     */
    public function validatePaymentMethod(?string $paymentMethod): array
    {
        $validPaymentMethods = ['Tiền mặt', 'Chuyển khoản'];

        if (empty($paymentMethod)) {
            return [
                'status' => 'failed',
                'validation_result' => 'Failed',
                'message' => 'Payment method is required',
            ];
        }

        if (!in_array($paymentMethod, $validPaymentMethods)) {
            return [
                'status' => 'failed',
                'validation_result' => 'Failed',
                'message' => 'Invalid payment method',
            ];
        }

        return [
            'status' => 'success',
            'paymentMethod' => $paymentMethod,
            'validation_result' => 'Successful',
        ];
    }

    /**
     * BR5: Checkout Validation Rules
     * If cart is empty → Set validation result = Failed
     * Else If any branch item exceeds branch stock → Set validation result = Failed
     * Else If shippingAddress invalid → Set validation result = Failed
     * Else If paymentMethod missing → Set validation result = Failed
     * Else Set validation result = Successful
     */
    public function validateCheckoutData($userId, array $shippingData, ?string $paymentMethod): array
    {
        // Check if cart is empty
        $cartDetails = $this->cartDetailRepository->getAllCartDetails($userId);
        if ($cartDetails->isEmpty()) {
            return [
                'status' => 'failed',
                'validation_result' => 'Failed',
                'message' => 'Cart is empty',
            ];
        }

        // Check if any branch item exceeds branch stock
        foreach ($cartDetails as $item) {
            $isEnough = $this->productDetailRepository->isEnoughQuantity(
                $item['product_id'],
                $item['branch_id'],
                $item['color'] ?? null,
                $item['quantity']
            );

            if (!$isEnough) {
                return [
                    'status' => 'failed',
                    'validation_result' => 'Failed',
                    'message' => "Item {$item['product_name']} exceeds branch stock",
                    'item' => $item,
                ];
            }
        }

        // Validate shipping address
        $shippingValidation = $this->validateShippingAddress($shippingData);
        if ($shippingValidation['validation_result'] === 'Failed') {
            return $shippingValidation;
        }

        // Validate payment method
        $paymentValidation = $this->validatePaymentMethod($paymentMethod);
        if ($paymentValidation['validation_result'] === 'Failed') {
            return $paymentValidation;
        }

        return [
            'status' => 'success',
            'validation_result' => 'Successful',
        ];
    }

    /**
     * BR6: Order Creation Rules
     * If validation result = Successful → Set order(s) = created per branch group
     * Update order status = Pending Payment
     * Else do not create order
     */
    public function createOrdersByBranch($userId, array $checkoutData): array
    {
        // Validate checkout data first
        $validation = $this->validateCheckoutData(
            $userId,
            $checkoutData['shipping'] ?? [],
            $checkoutData['payment_method'] ?? null
        );

        if ($validation['validation_result'] !== 'Successful') {
            return [
                'status' => 'failed',
                'message' => 'Checkout validation failed',
                'validation' => $validation,
            ];
        }

        // Get cart details grouped by branch
        $cartDetails = $this->cartDetailRepository->getAllCartDetails($userId);
        $groupedByBranch = $cartDetails->groupBy('branch_id');

        $createdOrders = [];

        // Create order for each branch
        foreach ($groupedByBranch as $branchId => $items) {
            $orderDetails = [];
            foreach ($items as $item) {
                $orderDetails[] = [
                    'product_id' => $item['product_id'],
                    'color' => $item['color'] ?? null,
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                ];
            }

            $orderData = [
                'branch_id' => $branchId,
                'address' => $checkoutData['shipping']['address'] ?? '',
                'note' => $checkoutData['shipping']['note'] ?? null,
                'coupon_id' => $checkoutData['coupon_id'] ?? null,
                'payment_method' => $checkoutData['payment_method'],
                'shipping_fee' => $checkoutData['shipping_fee'] ?? 0,
                'order_details' => $orderDetails,
            ];

            // Create order using OrderService
            $orderResponse = $this->orderService->store($orderData);
            $orderData = json_decode($orderResponse->getContent(), true);

            if ($orderData['status'] === 'success') {
                $createdOrders[] = $orderData['data'];
            }
        }

        if (empty($createdOrders)) {
            return [
                'status' => 'failed',
                'message' => 'Failed to create orders',
            ];
        }

        return [
            'status' => 'success',
            'orders' => $createdOrders,
        ];
    }

    /**
     * BR7: Result & Redirect Rules
     * If order created → Show confirmation and Redirect to payment flow
     * Else Show checkout error message and remain on checkout page
     */
    public function processCheckout($userId, array $checkoutData): array
    {
        // Create orders
        $orderResult = $this->createOrdersByBranch($userId, $checkoutData);

        if ($orderResult['status'] === 'success') {
            // Clear checkout session
            Session::forget('checkoutSession');

            return [
                'status' => 'success',
                'message' => 'Orders created successfully',
                'orders' => $orderResult['orders'],
                'redirect' => 'payment',
                'should_redirect' => true,
            ];
        }

        return [
            'status' => 'failed',
            'message' => $orderResult['message'] ?? 'Checkout failed',
            'should_redirect' => false,
        ];
    }

    /**
     * Check if checkout session is initiated
     */
    public function isCheckoutInitiated(): bool
    {
        return Session::get('checkoutSession') === 'initiated';
    }
}
