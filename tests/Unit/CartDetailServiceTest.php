<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\Branch;
use App\Models\ProductDetail;
use App\Repositories\CartDetailReposityInterface;
use App\Services\CartDetailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class CartDetailServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $cartDetailRepository;
    protected $cartDetailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartDetailRepository = Mockery::mock(CartDetailReposityInterface::class);
        $this->cartDetailService = new CartDetailService($this->cartDetailRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test store method thành công khi cart tồn tại
     */
    public function test_store_successfully_when_cart_exists(): void
    {
        // Arrange
        $userId = 1;
        $cartId = 1;
        $productId = 1;
        $branchId = 1;
        $color = 'black';
        $quantity = 2;

        $cart = new Cart();
        $cart->id = $cartId;
        $cart->user_id = $userId;

        $cartDetail = new CartDetail();
        $cartDetail->id = 1;
        $cartDetail->cart_id = $cartId;
        $cartDetail->product_id = $productId;
        $cartDetail->branch_id = $branchId;
        $cartDetail->color = $color;
        $cartDetail->quantity = $quantity;
        $cartDetail->total_price = 100;

        $data = [
            'user_id' => $userId,
            'product_id' => $productId,
            'branch_id' => $branchId,
            'color' => $color,
            'quantity' => $quantity,
        ];

        // Mock repository
        $this->cartDetailRepository
            ->shouldReceive('getCartByUserId')
            ->once()
            ->with($userId)
            ->andReturn($cart);

        $this->cartDetailRepository
            ->shouldReceive('store')
            ->once()
            ->with([
                'user_id' => $userId,
                'product_id' => $productId,
                'branch_id' => $branchId,
                'color' => $color,
                'quantity' => $quantity,
                'cart_id' => $cartId,
            ])
            ->andReturn($cartDetail);

        // Act
        $result = $this->cartDetailService->store($data);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('success', $resultData['status']);
        $this->assertArrayHasKey('data', $resultData);
    }

    /**
     * Test store method trả về lỗi khi cart không tồn tại
     */
    public function test_store_returns_error_when_cart_not_exists(): void
    {
        // Arrange
        $userId = 1;
        $data = [
            'user_id' => $userId,
            'product_id' => 1,
            'branch_id' => 1,
            'color' => 'black',
            'quantity' => 2,
        ];

        // Mock repository
        $this->cartDetailRepository
            ->shouldReceive('getCartByUserId')
            ->once()
            ->with($userId)
            ->andReturn(null);

        // Act
        $result = $this->cartDetailService->store($data);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('Cart not found', $resultData['message']);
        $this->assertEquals(404, $result->getStatusCode());
    }

    /**
     * Test store method với dữ liệu đầy đủ
     */
    public function test_store_with_complete_data(): void
    {
        // Arrange
        $userId = 1;
        $cartId = 1;
        $productId = 1;
        $branchId = 1;
        $color = 'red';
        $quantity = 5;

        $cart = new Cart();
        $cart->id = $cartId;
        $cart->user_id = $userId;

        $cartDetail = new CartDetail();
        $cartDetail->id = 1;
        $cartDetail->cart_id = $cartId;
        $cartDetail->product_id = $productId;
        $cartDetail->branch_id = $branchId;
        $cartDetail->color = $color;
        $cartDetail->quantity = $quantity;
        $cartDetail->total_price = 250;

        $data = [
            'user_id' => $userId,
            'product_id' => $productId,
            'branch_id' => $branchId,
            'color' => $color,
            'quantity' => $quantity,
        ];

        // Mock repository
        $this->cartDetailRepository
            ->shouldReceive('getCartByUserId')
            ->once()
            ->with($userId)
            ->andReturn($cart);

        $this->cartDetailRepository
            ->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($arg) use ($cartId, $productId, $branchId, $color, $quantity) {
                return $arg['cart_id'] === $cartId
                    && $arg['product_id'] === $productId
                    && $arg['branch_id'] === $branchId
                    && $arg['color'] === $color
                    && $arg['quantity'] === $quantity;
            }))
            ->andReturn($cartDetail);

        // Act
        $result = $this->cartDetailService->store($data);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('success', $resultData['status']);
        $this->assertArrayHasKey('data', $resultData);
    }

    /**
     * Test store method với color null
     */
    public function test_store_with_null_color(): void
    {
        // Arrange
        $userId = 1;
        $cartId = 1;
        $productId = 1;
        $branchId = 1;
        $quantity = 3;

        $cart = new Cart();
        $cart->id = $cartId;
        $cart->user_id = $userId;

        $cartDetail = new CartDetail();
        $cartDetail->id = 1;
        $cartDetail->cart_id = $cartId;
        $cartDetail->product_id = $productId;
        $cartDetail->branch_id = $branchId;
        $cartDetail->color = null;
        $cartDetail->quantity = $quantity;
        $cartDetail->total_price = 150;

        $data = [
            'user_id' => $userId,
            'product_id' => $productId,
            'branch_id' => $branchId,
            'color' => null,
            'quantity' => $quantity,
        ];

        // Mock repository
        $this->cartDetailRepository
            ->shouldReceive('getCartByUserId')
            ->once()
            ->with($userId)
            ->andReturn($cart);

        $this->cartDetailRepository
            ->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($arg) use ($cartId) {
                return $arg['cart_id'] === $cartId && $arg['color'] === null;
            }))
            ->andReturn($cartDetail);

        // Act
        $result = $this->cartDetailService->store($data);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('success', $resultData['status']);
    }

    /**
     * Test store method với số lượng lớn
     */
    public function test_store_with_large_quantity(): void
    {
        // Arrange
        $userId = 1;
        $cartId = 1;
        $productId = 1;
        $branchId = 1;
        $color = 'blue';
        $quantity = 100;

        $cart = new Cart();
        $cart->id = $cartId;
        $cart->user_id = $userId;

        $cartDetail = new CartDetail();
        $cartDetail->id = 1;
        $cartDetail->cart_id = $cartId;
        $cartDetail->product_id = $productId;
        $cartDetail->branch_id = $branchId;
        $cartDetail->color = $color;
        $cartDetail->quantity = $quantity;
        $cartDetail->total_price = 5000;

        $data = [
            'user_id' => $userId,
            'product_id' => $productId,
            'branch_id' => $branchId,
            'color' => $color,
            'quantity' => $quantity,
        ];

        // Mock repository
        $this->cartDetailRepository
            ->shouldReceive('getCartByUserId')
            ->once()
            ->with($userId)
            ->andReturn($cart);

        $this->cartDetailRepository
            ->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($arg) use ($quantity) {
                return $arg['quantity'] === $quantity;
            }))
            ->andReturn($cartDetail);

        // Act
        $result = $this->cartDetailService->store($data);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertEquals('success', $resultData['status']);
    }
}

