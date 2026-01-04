<?php

namespace Tests\Unit;

use App\Repositories\CartDetailReposityInterface;
use App\Repositories\Product\ProductDetailRepositoryInterface;
use App\Services\CheckoutService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Mockery;
use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $cartDetailRepository;
    protected $productDetailRepository;
    protected $orderService;
    protected $checkoutService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartDetailRepository = Mockery::mock(CartDetailReposityInterface::class);
        $this->productDetailRepository = Mockery::mock(ProductDetailRepositoryInterface::class);
        $this->orderService = Mockery::mock(OrderService::class);

        $this->checkoutService = new CheckoutService(
            $this->cartDetailRepository,
            $this->productDetailRepository,
            $this->orderService
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        Session::flush();
        parent::tearDown();
    }

    /**
     * BR1: Checkout Entry Rules
     * Test: If user clicks Checkout → Set checkoutSession = initiated
     * 
     * Ví dụ các cách log/debug trong test:
     */
    public function test_br1_initiate_checkout_sets_session_to_initiated(): void
    {
        // CÁCH 1: Sử dụng dump() - hiển thị giá trị và tiếp tục chạy
        $result = $this->checkoutService->initiateCheckout();
        dump('Result:', $result); // Hiển thị trong console output
        
        // CÁCH 2: Sử dụng dd() - dump and die (dừng test tại đây)
        // dd('Result:', $result); // Uncomment để dùng
        
        // CÁCH 3: Sử dụng Log facade - ghi vào log file
        Log::info('Checkout initiated', ['result' => $result]);
        Log::debug('Session value', ['session' => Session::get('checkoutSession')]);
        
        // CÁCH 4: Sử dụng var_dump() hoặc print_r()
        // var_dump($result); // Hiển thị với type information
        // print_r($result, true); // Return as string
        
        // CÁCH 5: Sử dụng PHPUnit's output methods (commented out để tránh fail test)
        // $this->expectOutputString(''); // Nếu muốn test output
        // echo "Result: " . json_encode($result) . "\n";
        
        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('initiated', $result['checkoutSession']);
        $this->assertTrue(Session::has('checkoutSession'));
        $this->assertEquals('initiated', Session::get('checkoutSession'));
    }

    /**
     * Ví dụ: Log nhiều biến cùng lúc
     */
    public function test_example_logging_multiple_variables(): void
    {
        $userId = 1;
        $cartDetails = collect([
            ['id' => 1, 'branch_id' => 1, 'quantity' => 2],
            ['id' => 2, 'branch_id' => 2, 'quantity' => 1],
        ]);

        // Log với context
        Log::channel('single')->info('Test variables', [
            'userId' => $userId,
            'cartDetails' => $cartDetails->toArray(),
            'cartCount' => $cartDetails->count(),
        ]);

        // Dump nhiều biến
        dump([
            'userId' => $userId,
            'cartDetails' => $cartDetails,
            'cartCount' => $cartDetails->count(),
        ]);

        // Assert để test không fail
        $this->assertTrue(true);
    }

    /**
     * Ví dụ: Log trong loop
     */
    public function test_example_logging_in_loop(): void
    {
        $items = [
            ['id' => 1, 'price' => 100000],
            ['id' => 2, 'price' => 200000],
        ];

        foreach ($items as $index => $item) {
            // Log từng iteration
            Log::debug("Processing item {$index}", [
                'item' => $item,
                'total' => array_sum(array_column($items, 'price')),
            ]);

            // Hoặc dump
            dump("Item {$index}:", $item);
        }

        $this->assertCount(2, $items);
    }

    /**
     * BR2: Review Rules
     * Test: If cart has items → Show items grouped by branch with subtotals
     */
    public function test_br2_review_cart_shows_items_grouped_by_branch_with_subtotals(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'product_name' => 'Product 1',
                'branches_name' => 'Branch 1',
                'color' => 'black',
                'quantity' => 2,
                'total_price' => 200000,
            ],
            [
                'id' => 2,
                'product_id' => 2,
                'branch_id' => 1,
                'product_name' => 'Product 2',
                'branches_name' => 'Branch 1',
                'color' => 'red',
                'quantity' => 1,
                'total_price' => 150000,
            ],
            [
                'id' => 3,
                'product_id' => 3,
                'branch_id' => 2,
                'product_name' => 'Product 3',
                'branches_name' => 'Branch 2',
                'color' => 'blue',
                'quantity' => 1,
                'total_price' => 100000,
            ],
        ]);

        // Log input data
        Log::info('Test input', [
            'userId' => $userId,
            'cartDetailsCount' => $cartDetails->count(),
            'cartDetails' => $cartDetails->toArray(),
        ]);

        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn($cartDetails);

        // Act
        $result = $this->checkoutService->reviewCartGroupedByBranch($userId);

        // Log result
        dump('Review cart result:', $result);
        Log::info('Review cart result', [
            'status' => $result['status'],
            'validation_result' => $result['validation_result'],
            'groupsCount' => count($result['data'] ?? []),
            'result' => $result,
        ]);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Successful', $result['validation_result']);
        $this->assertArrayHasKey('data', $result);
        $this->assertCount(2, $result['data']); // 2 branches

        // Log assertion details
        foreach ($result['data'] as $index => $group) {
            Log::debug("Branch group {$index}", [
                'branch_id' => $group['branch_id'],
                'branch_name' => $group['branch_name'],
                'itemsCount' => count($group['items']),
                'subtotal' => $group['subtotal'],
            ]);
        }

        // Check Branch 1
        $branch1 = collect($result['data'])->firstWhere('branch_id', 1);
        $this->assertNotNull($branch1);
        $this->assertEquals('Branch 1', $branch1['branch_name']);
        $this->assertEquals(350000, $branch1['subtotal']); // 200000 + 150000
        $this->assertCount(2, $branch1['items']);

        // Check Branch 2
        $branch2 = collect($result['data'])->firstWhere('branch_id', 2);
        $this->assertNotNull($branch2);
        $this->assertEquals('Branch 2', $branch2['branch_name']);
        $this->assertEquals(100000, $branch2['subtotal']);
        $this->assertCount(1, $branch2['items']);
    }

    /**
     * BR2: Review Rules
     * Test: Else Set validation result = Failed
     */
    public function test_br2_review_cart_returns_failed_when_cart_is_empty(): void
    {
        // Arrange
        $userId = 1;
        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn(collect([]));

        // Act
        $result = $this->checkoutService->reviewCartGroupedByBranch($userId);

        // Debug: Log empty cart scenario
        dump('Empty cart result:', $result);
        Log::warning('Empty cart detected', [
            'userId' => $userId,
            'result' => $result,
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
        $this->assertEquals('Cart is empty', $result['message']);
    }

    /**
     * BR3: Shipping Rules
     * Test: If user selects saved address → Set shippingAddress = selected
     */
    public function test_br3_validate_shipping_with_saved_address_returns_selected(): void
    {
        // Arrange
        $shippingData = [
            'saved_address_id' => 1,
        ];

        // Log input
        dump('Shipping data:', $shippingData);

        // Act
        $result = $this->checkoutService->validateShippingAddress($shippingData);

        // Log result với format đẹp
        Log::info('Shipping validation result', [
            'input' => $shippingData,
            'output' => $result,
            'status' => $result['status'] ?? 'unknown',
            'validation_result' => $result['validation_result'] ?? 'unknown',
        ]);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Successful', $result['validation_result']);
        $this->assertEquals('saved', $result['shippingAddress']['type']);
        $this->assertEquals(1, $result['shippingAddress']['address_id']);
    }

    /**
     * BR3: Shipping Rules
     * Test: Else Set shippingAddress = new input
     */
    public function test_br3_validate_shipping_with_new_address_returns_new_input(): void
    {
        // Arrange
        $shippingData = [
            'address' => '123 Test Street, Ward 1, District 1, Ho Chi Minh City',
            'note' => 'Test note',
        ];

        // Act
        $result = $this->checkoutService->validateShippingAddress($shippingData);

        // Debug: Sử dụng var_export để format đẹp hơn
        $debugInfo = [
            'input_address' => $shippingData['address'],
            'input_note' => $shippingData['note'] ?? 'null',
            'output_status' => $result['status'],
            'output_type' => $result['shippingAddress']['type'] ?? 'null',
            'output_address' => $result['shippingAddress']['address'] ?? 'null',
        ];
        
        dump('Shipping validation debug:', $debugInfo);
        Log::debug('New address validation', $debugInfo);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Successful', $result['validation_result']);
        $this->assertEquals('new', $result['shippingAddress']['type']);
        $this->assertEquals('123 Test Street, Ward 1, District 1, Ho Chi Minh City', $result['shippingAddress']['address']);
        $this->assertEquals('Test note', $result['shippingAddress']['note']);
    }

    /**
     * BR3: Shipping Rules
     * Test: If required fields missing → Set validation result = Failed
     */
    public function test_br3_validate_shipping_returns_failed_when_required_fields_missing(): void
    {
        // Arrange
        $shippingData = [
            // 'address' => missing
            'note' => 'Test note',
        ];

        // Act
        $result = $this->checkoutService->validateShippingAddress($shippingData);

        // Log validation failure
        Log::warning('Shipping validation failed', [
            'input' => $shippingData,
            'result' => $result,
            'missing_fields' => $result['missing_fields'] ?? [],
        ]);

        dump('Validation failed:', [
            'status' => $result['status'],
            'message' => $result['message'],
            'missing_fields' => $result['missing_fields'] ?? [],
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
        $this->assertEquals('Required shipping fields are missing', $result['message']);
        $this->assertContains('address', $result['missing_fields']);
    }

    /**
     * BR3: Shipping Rules
     * Test: If address is empty string → Set validation result = Failed
     */
    public function test_br3_validate_shipping_returns_failed_when_address_is_empty_string(): void
    {
        // Arrange
        $shippingData = [
            'address' => '   ', // Only whitespace
        ];

        // Act
        $result = $this->checkoutService->validateShippingAddress($shippingData);

        // Debug empty string scenario
        dump('Empty string validation:', [
            'input' => $shippingData,
            'address_length' => strlen($shippingData['address']),
            'address_trimmed_length' => strlen(trim($shippingData['address'])),
            'result' => $result,
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
    }

    /**
     * BR4: Payment Selection Rules
     * Test: If user selects a payment method → Set paymentMethod = selected
     */
    public function test_br4_validate_payment_method_returns_selected_when_valid(): void
    {
        // Test Tiền mặt
        $paymentMethod1 = 'Tiền mặt';
        $result1 = $this->checkoutService->validatePaymentMethod($paymentMethod1);
        
        dump("Payment method '{$paymentMethod1}':", $result1);
        
        $this->assertEquals('success', $result1['status']);
        $this->assertEquals('Successful', $result1['validation_result']);
        $this->assertEquals('Tiền mặt', $result1['paymentMethod']);

        // Test Chuyển khoản
        $paymentMethod2 = 'Chuyển khoản';
        $result2 = $this->checkoutService->validatePaymentMethod($paymentMethod2);
        
        dump("Payment method '{$paymentMethod2}':", $result2);
        Log::info('Payment method validation', [
            'method' => $paymentMethod2,
            'result' => $result2,
        ]);

        $this->assertEquals('success', $result2['status']);
        $this->assertEquals('Successful', $result2['validation_result']);
        $this->assertEquals('Chuyển khoản', $result2['paymentMethod']);
    }

    /**
     * BR4: Payment Selection Rules
     * Test: Else Set validation result = Failed
     */
    public function test_br4_validate_payment_method_returns_failed_when_missing(): void
    {
        // Act
        $result = $this->checkoutService->validatePaymentMethod(null);

        // Debug null payment method
        dump('Null payment method result:', $result);
        Log::warning('Payment method missing', ['result' => $result]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
        $this->assertEquals('Payment method is required', $result['message']);
    }

    /**
     * BR4: Payment Selection Rules
     * Test: Else Set validation result = Failed for invalid method
     */
    public function test_br4_validate_payment_method_returns_failed_when_invalid(): void
    {
        $invalidMethod = 'Invalid Method';
        
        // Act
        $result = $this->checkoutService->validatePaymentMethod($invalidMethod);

        // Debug invalid method
        dump('Invalid payment method:', [
            'input' => $invalidMethod,
            'result' => $result,
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
        $this->assertEquals('Invalid payment method', $result['message']);
    }

    /**
     * BR5: Checkout Validation Rules
     * Test: If cart is empty → Set validation result = Failed
     */
    public function test_br5_validate_checkout_returns_failed_when_cart_is_empty(): void
    {
        // Arrange
        $userId = 1;
        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn(collect([]));

        // Act
        $result = $this->checkoutService->validateCheckoutData($userId, [], null);

        // Debug empty cart validation
        dump('Empty cart validation:', [
            'userId' => $userId,
            'shippingData' => [],
            'paymentMethod' => null,
            'result' => $result,
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
        $this->assertEquals('Cart is empty', $result['message']);
    }

    /**
     * BR5: Checkout Validation Rules
     * Test: Else If any branch item exceeds branch stock → Set validation result = Failed
     */
    public function test_br5_validate_checkout_returns_failed_when_item_exceeds_stock(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'product_name' => 'Product 1',
                'color' => 'black',
                'quantity' => 10,
            ],
        ]);

        // Log input
        Log::info('Stock validation test', [
            'cartDetails' => $cartDetails->toArray(),
        ]);

        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn($cartDetails);

        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 10)
            ->andReturn(false); // Not enough stock

        // Act
        $result = $this->checkoutService->validateCheckoutData($userId, ['address' => 'Test'], 'Tiền mặt');

        // Debug stock validation failure
        dump('Stock validation failed:', [
            'item' => $cartDetails->first(),
            'result' => $result,
            'message' => $result['message'],
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
        $this->assertStringContainsString('exceeds branch stock', $result['message']);
    }

    /**
     * BR5: Checkout Validation Rules
     * Test: Else If shippingAddress invalid → Set validation result = Failed
     */
    public function test_br5_validate_checkout_returns_failed_when_shipping_address_invalid(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'color' => 'black',
                'quantity' => 2,
            ],
        ]);

        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn($cartDetails);

        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->andReturn(true);

        // Act - missing address
        $result = $this->checkoutService->validateCheckoutData($userId, [], 'Tiền mặt');

        // Debug shipping address validation
        dump('Shipping address validation failed:', $result);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
    }

    /**
     * BR5: Checkout Validation Rules
     * Test: Else If paymentMethod missing → Set validation result = Failed
     */
    public function test_br5_validate_checkout_returns_failed_when_payment_method_missing(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'color' => 'black',
                'quantity' => 2,
            ],
        ]);

        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn($cartDetails);

        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->checkoutService->validateCheckoutData($userId, ['address' => 'Test'], null);

        // Debug payment method validation
        Log::warning('Payment method missing in checkout', [
            'result' => $result,
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Failed', $result['validation_result']);
    }

    /**
     * BR5: Checkout Validation Rules
     * Test: Else Set validation result = Successful
     */
    public function test_br5_validate_checkout_returns_successful_when_all_valid(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'color' => 'black',
                'quantity' => 2,
            ],
        ]);

        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn($cartDetails);

        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 2)
            ->andReturn(true);

        // Act
        $result = $this->checkoutService->validateCheckoutData(
            $userId,
            ['address' => '123 Test Street'],
            'Tiền mặt'
        );

        // Debug successful validation
        dump('✅ Validation successful:', [
            'status' => $result['status'],
            'validation_result' => $result['validation_result'],
        ]);
        Log::info('✅ Checkout validation passed', ['result' => $result]);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Successful', $result['validation_result']);
    }

    // ... (giữ nguyên các test methods còn lại)
    
    /**
     * BR6: Order Creation Rules
     * Test: If validation result = Successful → Set order(s) = created per branch group
     */
    public function test_br6_create_orders_creates_orders_per_branch_when_validation_successful(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'product_name' => 'Product 1',
                'color' => 'black',
                'quantity' => 2,
                'total_price' => 200000,
            ],
            [
                'id' => 2,
                'product_id' => 2,
                'branch_id' => 2,
                'product_name' => 'Product 2',
                'color' => 'red',
                'quantity' => 1,
                'total_price' => 150000,
            ],
        ]);

        $checkoutData = [
            'shipping' => [
                'address' => '123 Test Street',
            ],
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
        ];

        // Log test setup
        dump('Test setup:', [
            'userId' => $userId,
            'cartDetailsCount' => $cartDetails->count(),
            'checkoutData' => $checkoutData,
        ]);

        // Mock cart details
        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->twice() // Called in validateCheckoutData and createOrdersByBranch
            ->with($userId)
            ->andReturn($cartDetails);

        // Mock stock validation
        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->twice()
            ->andReturn(true);

        // Mock order creation
        $order1 = (object)['id' => 1, 'branch_id' => 1];
        $order2 = (object)['id' => 2, 'branch_id' => 2];

        $orderResponse1 = new JsonResponse([
            'status' => 'success',
            'data' => $order1,
        ], 200);

        $orderResponse2 = new JsonResponse([
            'status' => 'success',
            'data' => $order2,
        ], 200);

        $this->orderService
            ->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($arg) {
                dump('Order 1 data:', $arg);
                return $arg['branch_id'] === 1 && count($arg['order_details']) === 1;
            }))
            ->andReturn($orderResponse1);

        $this->orderService
            ->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($arg) {
                dump('Order 2 data:', $arg);
                return $arg['branch_id'] === 2 && count($arg['order_details']) === 1;
            }))
            ->andReturn($orderResponse2);

        // Act
        $result = $this->checkoutService->createOrdersByBranch($userId, $checkoutData);

        // Log result
        dump('Orders created:', [
            'status' => $result['status'],
            'ordersCount' => count($result['orders'] ?? []),
            'orders' => $result['orders'] ?? [],
        ]);
        Log::info('Orders created successfully', [
            'ordersCount' => count($result['orders'] ?? []),
            'result' => $result,
        ]);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertCount(2, $result['orders']); // 2 orders for 2 branches
    }

    /**
     * BR6: Order Creation Rules
     * Test: Else do not create order
     */
    public function test_br6_create_orders_does_not_create_when_validation_failed(): void
    {
        // Arrange
        $userId = 1;
        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn(collect([])); // Empty cart

        $checkoutData = [
            'shipping' => ['address' => 'Test'],
            'payment_method' => 'Tiền mặt',
        ];

        // Act
        $result = $this->checkoutService->createOrdersByBranch($userId, $checkoutData);

        // Debug validation failure
        dump('❌ Order creation failed:', [
            'status' => $result['status'],
            'message' => $result['message'],
            'validation' => $result['validation'] ?? null,
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Checkout validation failed', $result['message']);
        $this->orderService->shouldNotHaveReceived('store');
    }

    /**
     * BR7: Result & Redirect Rules
     * Test: If order created → Show confirmation and Redirect to payment flow
     */
    public function test_br7_process_checkout_returns_success_with_redirect_when_orders_created(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'color' => 'black',
                'quantity' => 2,
                'total_price' => 200000,
            ],
        ]);

        $checkoutData = [
            'shipping' => ['address' => '123 Test Street'],
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
        ];

        // Mock cart details
        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->twice()
            ->with($userId)
            ->andReturn($cartDetails);

        // Mock stock validation
        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 2)
            ->andReturn(true);

        // Mock order creation
        $order = (object)['id' => 1, 'branch_id' => 1];
        $orderResponse = new JsonResponse([
            'status' => 'success',
            'data' => $order,
        ], 200);

        $this->orderService
            ->shouldReceive('store')
            ->once()
            ->andReturn($orderResponse);

        // Act
        $result = $this->checkoutService->processCheckout($userId, $checkoutData);

        // Debug successful checkout
        dump('✅ Checkout successful:', [
            'status' => $result['status'],
            'should_redirect' => $result['should_redirect'],
            'redirect' => $result['redirect'] ?? null,
            'ordersCount' => count($result['orders'] ?? []),
        ]);
        Log::info('✅ Checkout processed successfully', [
            'result' => $result,
            'sessionCleared' => !Session::has('checkoutSession'),
        ]);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Orders created successfully', $result['message']);
        $this->assertTrue($result['should_redirect']);
        $this->assertEquals('payment', $result['redirect']);
        $this->assertFalse(Session::has('checkoutSession')); // Session cleared
    }

    /**
     * BR7: Result & Redirect Rules
     * Test: Else Show checkout error message and remain on checkout page
     */
    public function test_br7_process_checkout_returns_failed_without_redirect_when_orders_not_created(): void
    {
        // Arrange
        $userId = 1;
        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn(collect([])); // Empty cart

        $checkoutData = [
            'shipping' => ['address' => 'Test'],
            'payment_method' => 'Tiền mặt',
        ];

        // Act
        $result = $this->checkoutService->processCheckout($userId, $checkoutData);

        // Debug failed checkout
        dump('❌ Checkout failed:', [
            'status' => $result['status'],
            'message' => $result['message'],
            'should_redirect' => $result['should_redirect'],
        ]);
        Log::error('❌ Checkout processing failed', [
            'userId' => $userId,
            'result' => $result,
        ]);

        // Assert
        $this->assertEquals('failed', $result['status']);
        $this->assertFalse($result['should_redirect']);
        $this->assertStringContainsString('failed', $result['message']);
    }

    /**
     * Test: Multiple items in same branch are grouped correctly
     */
    public function test_multiple_items_same_branch_grouped_correctly(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'product_name' => 'Product 1',
                'branches_name' => 'Branch 1',
                'color' => 'black',
                'quantity' => 2,
                'total_price' => 200000,
            ],
            [
                'id' => 2,
                'product_id' => 2,
                'branch_id' => 1,
                'product_name' => 'Product 2',
                'branches_name' => 'Branch 1',
                'color' => 'red',
                'quantity' => 1,
                'total_price' => 150000,
            ],
        ]);

        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->once()
            ->with($userId)
            ->andReturn($cartDetails);

        // Act
        $result = $this->checkoutService->reviewCartGroupedByBranch($userId);

        // Debug grouping
        dump('Grouped by branch:', [
            'groupsCount' => count($result['data']),
            'groups' => $result['data'],
        ]);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertCount(1, $result['data']); // Only 1 branch
        $branch = $result['data'][0];
        $this->assertCount(2, $branch['items']); // 2 items in branch
        $this->assertEquals(350000, $branch['subtotal']); // 200000 + 150000
    }

    /**
     * Test: Order creation with coupon
     */
    public function test_create_orders_with_coupon(): void
    {
        // Arrange
        $userId = 1;
        $cartDetails = collect([
            [
                'id' => 1,
                'product_id' => 1,
                'branch_id' => 1,
                'color' => 'black',
                'quantity' => 2,
                'total_price' => 200000,
            ],
        ]);

        $checkoutData = [
            'shipping' => ['address' => '123 Test Street'],
            'payment_method' => 'Tiền mặt',
            'coupon_id' => 1,
            'shipping_fee' => 20000,
        ];

        $this->cartDetailRepository
            ->shouldReceive('getAllCartDetails')
            ->twice()
            ->with($userId)
            ->andReturn($cartDetails);

        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 2)
            ->andReturn(true);

        $order = (object)['id' => 1];
        $orderResponse = new JsonResponse([
            'status' => 'success',
            'data' => $order,
        ], 200);

        $this->orderService
            ->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($arg) {
                dump('Order with coupon:', [
                    'coupon_id' => $arg['coupon_id'] ?? null,
                    'branch_id' => $arg['branch_id'],
                ]);
                return $arg['coupon_id'] === 1;
            }))
            ->andReturn($orderResponse);

        // Act
        $result = $this->checkoutService->createOrdersByBranch($userId, $checkoutData);

        // Assert
        $this->assertEquals('success', $result['status']);
    }
}
