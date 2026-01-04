<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\CouponRepositoryInterface;
use App\Repositories\Product\ProductDetailRepositoryInterface;
use App\Repositories\OrderDetailRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderRepository;
    protected $couponRepository;
    protected $productDetailRepository;
    protected $orderDetailRepository;
    protected $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderRepository = Mockery::mock(OrderRepositoryInterface::class);
        $this->couponRepository = Mockery::mock(CouponRepositoryInterface::class);
        $this->productDetailRepository = Mockery::mock(ProductDetailRepositoryInterface::class);
        $this->orderDetailRepository = Mockery::mock(OrderDetailRepositoryInterface::class);
        
        $this->orderService = new OrderService(
            $this->orderRepository,
            $this->couponRepository,
            $this->productDetailRepository,
            $this->orderDetailRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test store method thành công với valid data
     */
    public function test_store_successfully_with_valid_data(): void
    {
        // Arrange
        $orderData = [
            'branch_id' => 1,
            'address' => '123 Test Street',
            'note' => 'Test note',
            'coupon_id' => null,
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => 1,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
            ],
        ];

        $order = new Order();
        $order->id = 1;
        $order->user_id = 1;
        $order->branch_id = 1;
        $order->total_price = 200000;

        // Mock repositories
        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 2)
            ->andReturn(true);

        $this->orderRepository
            ->shouldReceive('store')
            ->once()
            ->andReturn($order);

        $this->orderDetailRepository
            ->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return $arg['order_id'] === 1
                    && $arg['product_id'] === 1
                    && $arg['quantity'] === 2;
            }));

        // Act
        $result = $this->orderService->store($orderData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('success', $resultData['status']);
        $this->assertArrayHasKey('data', $resultData);
    }

    /**
     * Test store method với item exceeding branch stock
     */
    public function test_store_returns_error_when_item_exceeds_branch_stock(): void
    {
        // Arrange
        $orderData = [
            'branch_id' => 1,
            'address' => '123 Test Street',
            'order_details' => [
                [
                    'product_id' => 1,
                    'color' => 'black',
                    'quantity' => 10,
                    'total_price' => 1000000,
                ],
            ],
        ];

        // Mock repository - không đủ số lượng
        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 10)
            ->andReturn(false);

        // Act
        $result = $this->orderService->store($orderData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('fail', $resultData['status']);
        $this->assertStringContainsString('quantity', $resultData['message']);
        $this->assertEquals(422, $result->getStatusCode());
    }

    /**
     * Test store method với coupon
     */
    public function test_store_with_valid_coupon(): void
    {
        // Arrange
        $orderData = [
            'branch_id' => 1,
            'address' => '123 Test Street',
            'coupon_id' => 1,
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => 1,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
            ],
        ];

        $coupon = new \App\Models\Coupon();
        $coupon->id = 1;
        $coupon->discount_price = 50000;

        $order = new Order();
        $order->id = 1;
        $order->total_price = 150000; // 200000 - 50000

        // Mock repositories
        $this->couponRepository
            ->shouldReceive('getById')
            ->once()
            ->with(1)
            ->andReturn($coupon);

        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->andReturn(true);

        $this->orderRepository
            ->shouldReceive('store')
            ->once()
            ->andReturn($order);

        $this->orderDetailRepository
            ->shouldReceive('store')
            ->once();

        // Act
        $result = $this->orderService->store($orderData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('success', $resultData['status']);
    }

    /**
     * Test store method với multiple order details
     */
    public function test_store_with_multiple_order_details(): void
    {
        // Arrange
        $orderData = [
            'branch_id' => 1,
            'address' => '123 Test Street',
            'coupon_id' => null,
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => 1,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
                [
                    'product_id' => 2,
                    'color' => 'red',
                    'quantity' => 1,
                    'total_price' => 150000,
                ],
            ],
        ];

        $order = new Order();
        $order->id = 1;
        $order->total_price = 350000;

        // Mock repositories
        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 2)
            ->andReturn(true);

        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(2, 1, 'red', 1)
            ->andReturn(true);

        $this->orderRepository
            ->shouldReceive('store')
            ->once()
            ->andReturn($order);

        $this->orderDetailRepository
            ->shouldReceive('store')
            ->twice(); // Called for each order detail

        // Act
        $result = $this->orderService->store($orderData);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('success', $resultData['status']);
    }

    /**
     * Test isEnoughQuantity method
     */
    public function test_isEnoughQuantity_returns_true_when_quantity_is_sufficient(): void
    {
        // Arrange
        $orderData = [
            'branch_id' => 1,
            'order_details' => [
                [
                    'product_id' => 1,
                    'branch_id' => 1,
                    'color' => 'black',
                    'quantity' => 5,
                ],
            ],
        ];

        // Mock repository
        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 5)
            ->andReturn(true);

        // Act
        $result = $this->orderService->isEnoughQuantityProduct($orderData);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test isEnoughQuantity method returns false when insufficient
     */
    public function test_isEnoughQuantity_returns_false_when_insufficient(): void
    {
        // Arrange
        $orderData = [
            'branch_id' => 1,
            'order_details' => [
                [
                    'product_id' => 1,
                    'branch_id' => 1,
                    'color' => 'black',
                    'quantity' => 100,
                ],
            ],
        ];

        // Mock repository
        $this->productDetailRepository
            ->shouldReceive('isEnoughQuantity')
            ->once()
            ->with(1, 1, 'black', 100)
            ->andReturn(false);

        // Act
        $result = $this->orderService->isEnoughQuantityProduct($orderData);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test calculateDiscountPrice với coupon
     */
    public function test_calculateDiscountPrice_with_coupon(): void
    {
        // Arrange
        $orderData = [
            'coupon_id' => 1,
        ];

        $coupon = new \App\Models\Coupon();
        $coupon->discount_price = 50000;

        // Mock repository
        $this->couponRepository
            ->shouldReceive('getById')
            ->once()
            ->with(1)
            ->andReturn($coupon);

        // Act
        $result = $this->orderService->calculateDiscountPrice($orderData);

        // Assert
        $this->assertEquals(50000, $result);
    }

    /**
     * Test calculateDiscountPrice without coupon
     */
    public function test_calculateDiscountPrice_without_coupon(): void
    {
        // Arrange
        $orderData = [
            'coupon_id' => null,
        ];

        // Act
        $result = $this->orderService->calculateDiscountPrice($orderData);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test calculateDiscountPrice với invalid coupon_id
     */
    public function test_calculateDiscountPrice_with_invalid_coupon_returns_zero(): void
    {
        // Arrange
        $orderData = [
            'coupon_id' => 99999,
        ];

        // Mock repository throws exception
        $this->couponRepository
            ->shouldReceive('getById')
            ->once()
            ->with(99999)
            ->andThrow(new \Exception('Coupon not found'));

        // Act
        $result = $this->orderService->calculateDiscountPrice($orderData);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test prepareForOrderData tính total_price đúng
     */
    public function test_prepareForOrderData_calculates_total_price_correctly(): void
    {
        // Arrange
        $orderData = [
            'branch_id' => 1,
            'order_details' => [
                [
                    'total_price' => 200000,
                ],
                [
                    'total_price' => 150000,
                ],
            ],
        ];

        $discountPrice = 50000;

        // Act
        $this->orderService->prepareForOrderData($orderData, $discountPrice);

        // Assert
        $this->assertEquals(300000, $orderData['total_price']); // 200000 + 150000 - 50000
        $this->assertArrayHasKey('user_id', $orderData);
        $this->assertArrayHasKey('date', $orderData);
    }

    /**
     * Test prepareForOrderData với discount lớn hơn total
     */
    public function test_prepareForOrderData_with_discount_exceeding_total_returns_zero(): void
    {
        // Arrange
        $orderData = [
            'branch_id' => 1,
            'order_details' => [
                [
                    'total_price' => 50000,
                ],
            ],
        ];

        $discountPrice = 100000; // Lớn hơn total

        // Act
        $this->orderService->prepareForOrderData($orderData, $discountPrice);

        // Assert
        $this->assertEquals(0, $orderData['total_price']); // Không được âm
    }
}

