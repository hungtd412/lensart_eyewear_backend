<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Branch;
use App\Models\ProductDetail;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRequiredData();
    }

    /**
     * Tạo dữ liệu cần thiết cho test
     */
    protected function seedRequiredData(): void
    {
        // Tạo role nếu chưa có
        if (!DB::table('role')->where('id', 1)->exists()) {
            DB::table('role')->insert([
                ['id' => 1, 'name' => 'Admin'],
                ['id' => 2, 'name' => 'Manager'],
                ['id' => 3, 'name' => 'Customer'],
            ]);
        }

        // Tạo category nếu chưa có
        if (!DB::table('category')->where('id', 1)->exists()) {
            DB::table('category')->insert([
                ['id' => 1, 'name' => 'Category 1'],
                ['id' => 2, 'name' => 'Category 2'],
                ['id' => 3, 'name' => 'Category 3'],
            ]);
        }

        // Tạo brand nếu chưa có
        if (!DB::table('brands')->where('id', 1)->exists()) {
            DB::table('brands')->insert([
                ['id' => 1, 'name' => 'Brand 1'],
                ['id' => 2, 'name' => 'Brand 2'],
                ['id' => 3, 'name' => 'Brand 3'],
            ]);
        }

        // Tạo material nếu chưa có
        if (!DB::table('materials')->where('id', 1)->exists()) {
            DB::table('materials')->insert([
                ['id' => 1, 'name' => 'Material 1'],
                ['id' => 2, 'name' => 'Material 2'],
                ['id' => 3, 'name' => 'Material 3'],
            ]);
        }

        // Tạo shape nếu chưa có
        if (!DB::table('shapes')->where('id', 1)->exists()) {
            DB::table('shapes')->insert([
                ['id' => 1, 'name' => 'Shape 1'],
                ['id' => 2, 'name' => 'Shape 2'],
                ['id' => 3, 'name' => 'Shape 3'],
            ]);
        }

        // Tạo manager user cho branch nếu chưa có
        if (!User::where('email', 'test-manager@example.com')->exists()) {
            User::factory()->create([
                'email' => 'test-manager@example.com',
                'role_id' => 2, // Manager role
            ]);
        }
    }

    /**
     * Helper method để tạo user với role customer
     */
    protected function createCustomerUser(): User
    {
        return User::factory()->create(['role_id' => 3]);
    }

    /**
     * Helper method để tạo cart với items
     */
    protected function createCartWithItems(User $user, array $items = []): Cart
    {
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        
        if (empty($items)) {
            // Tạo default items
            $product = Product::factory()->create(['status' => 'active']);
            $branch = Branch::factory()->create(['status' => 'active']);
            $productDetail = ProductDetail::factory()->create([
                'product_id' => $product->id,
                'branch_id' => $branch->id,
                'color' => 'black',
                'quantity' => 10,
                'status' => 'active',
            ]);

            $items = [
                [
                    'product_id' => $product->id,
                    'branch_id' => $branch->id,
                    'color' => 'black',
                    'quantity' => 2,
                ],
            ];
        }

        return $cart;
    }

    /**
     * Test checkout với valid cart, shipping info, và payment method
     */
    public function test_checkout_with_valid_cart_shipping_info_and_payment_method(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo cart
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        // Tạo product, branch và product detail
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 10,
            'status' => 'active',
        ]);

        // Tạo cart detail
        $cartDetail = \App\Models\CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 2,
            'total_price' => 200000,
        ]);

        // Dữ liệu order
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street, Ward 1, District 1, Ho Chi Minh City',
            'note' => 'Test note',
            'coupon_id' => null,
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'user_id',
                    'branch_id',
                    'address',
                    'total_price',
                    'payment_method',
                ],
            ])
            ->assertJson([
                'status' => 'success',
            ]);

        // Kiểm tra database
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'branch_id' => $branch->id,
            'address' => '123 Test Street, Ward 1, District 1, Ho Chi Minh City',
            'payment_method' => 'Tiền mặt',
        ]);

        // Kiểm tra order details được tạo
        $orderId = $response->json('data.id');
        $this->assertDatabaseHas('order_details', [
            'order_id' => $orderId,
            'product_id' => $product->id,
            'color' => 'black',
            'quantity' => 2,
        ]);
    }

    /**
     * Test attempt checkout với empty cart (không có order_details)
     */
    public function test_attempt_checkout_with_empty_cart(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo branch
        $branch = Branch::factory()->create(['status' => 'active']);

        // Dữ liệu order với empty order_details
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street',
            'note' => null,
            'coupon_id' => null,
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [], // Empty array
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra validation error
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('order_details', $errorData);
    }

    /**
     * Test checkout với missing required shipping fields
     */
    public function test_checkout_with_missing_required_shipping_fields(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Test missing branch_id
        $orderData = [
            // 'branch_id' => missing
            'address' => '123 Test Street',
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                    'total_price' => 100000,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders/create', $orderData);
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('branch_id', $errorData);

        // Test missing address
        $branch = Branch::factory()->create(['status' => 'active']);
        $orderData = [
            'branch_id' => $branch->id,
            // 'address' => missing
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                    'total_price' => 100000,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders/create', $orderData);
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('address', $errorData);
    }

    /**
     * Test checkout without selecting payment method
     */
    public function test_checkout_without_selecting_payment_method(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product, branch và product detail
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 10,
            'status' => 'active',
        ]);

        // Dữ liệu order thiếu payment_method
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street, Ward 1, District 1, Ho Chi Minh City',
            'note' => null,
            'coupon_id' => null,
            // 'payment_method' => missing
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra validation error
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('payment_method', $errorData);
    }

    /**
     * Test checkout với invalid payment method
     */
    public function test_checkout_with_invalid_payment_method(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product, branch và product detail
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 10,
            'status' => 'active',
        ]);

        // Dữ liệu order với invalid payment_method
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street',
            'payment_method' => 'Invalid Method', // Invalid
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra validation error
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('payment_method', $errorData);
    }

    /**
     * Test checkout với item exceeding branch stock
     */
    public function test_checkout_with_item_exceeding_branch_stock(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product, branch và product detail với số lượng thấp
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 5, // Chỉ có 5 sản phẩm
            'status' => 'active',
        ]);

        // Dữ liệu order với quantity vượt quá stock
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street, Ward 1, District 1, Ho Chi Minh City',
            'note' => null,
            'coupon_id' => null,
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    'quantity' => 10, // Yêu cầu 10 nhưng chỉ có 5
                    'total_price' => 1000000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra response - should return error
        $response->assertStatus(422)
            ->assertJson([
                'status' => 'fail',
                'message' => 'The quantity of products ordered exceeds the quantity of available products',
            ]);
    }

    /**
     * Test checkout với valid coupon
     */
    public function test_checkout_with_valid_coupon(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product, branch và product detail
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 10,
            'status' => 'active',
        ]);

        // Tạo coupon
        $coupon = Coupon::factory()->create([
            'status' => 'active',
            'quantity' => 10,
            'discount_price' => 50000,
        ]);

        // Dữ liệu order với coupon
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street, Ward 1, District 1, Ho Chi Minh City',
            'note' => null,
            'coupon_id' => $coupon->id,
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ])
            ->assertJson([
                'status' => 'success',
            ]);

        // Kiểm tra order có coupon_id
        $orderId = $response->json('data.id');
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'coupon_id' => $coupon->id,
        ]);
    }

    /**
     * Test checkout với Chuyển khoản payment method
     */
    public function test_checkout_with_bank_transfer_payment_method(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product, branch và product detail
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 10,
            'status' => 'active',
        ]);

        // Dữ liệu order với Chuyển khoản
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street, Ward 1, District 1, Ho Chi Minh City',
            'note' => null,
            'coupon_id' => null,
            'payment_method' => 'Chuyển khoản',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        // Kiểm tra payment_method
        $orderId = $response->json('data.id');
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'payment_method' => 'Chuyển khoản',
        ]);
    }

    /**
     * Test checkout với missing shipping_fee
     */
    public function test_checkout_with_missing_shipping_fee(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product, branch và product detail
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 10,
            'status' => 'active',
        ]);

        // Dữ liệu order thiếu shipping_fee
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street',
            'payment_method' => 'Tiền mặt',
            // 'shipping_fee' => missing
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    'quantity' => 2,
                    'total_price' => 200000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra validation error
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('shipping_fee', $errorData);
    }

    /**
     * Test checkout với invalid branch_id
     */
    public function test_checkout_with_invalid_branch_id(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Dữ liệu order với branch_id không tồn tại
        $orderData = [
            'branch_id' => 99999, // Không tồn tại
            'address' => '123 Test Street',
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                    'total_price' => 100000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra validation error
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('branch_id', $errorData);
    }

    /**
     * Test checkout với invalid product_id trong order_details
     */
    public function test_checkout_with_invalid_product_id_in_order_details(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo branch
        $branch = Branch::factory()->create(['status' => 'active']);

        // Dữ liệu order với product_id không tồn tại
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street',
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => 99999, // Không tồn tại
                    'color' => 'black',
                    'quantity' => 1,
                    'total_price' => 100000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra validation error
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('order_details.0.product_id', $errorData);
    }

    /**
     * Test checkout với missing quantity trong order_details
     */
    public function test_checkout_with_missing_quantity_in_order_details(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product và branch
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);

        // Dữ liệu order thiếu quantity
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street',
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    // 'quantity' => missing
                    'total_price' => 100000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra validation error
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('order_details.0.quantity', $errorData);
    }

    /**
     * Test checkout với invalid quantity (less than 1)
     */
    public function test_checkout_with_invalid_quantity_less_than_one(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product và branch
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);

        // Dữ liệu order với quantity = 0
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street',
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => $product->id,
                    'color' => 'black',
                    'quantity' => 0, // Invalid
                    'total_price' => 100000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra validation error
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);

        $errorData = $response->json('error');
        $this->assertArrayHasKey('order_details.0.quantity', $errorData);
    }

    /**
     * Test checkout khi chưa đăng nhập
     */
    public function test_checkout_without_authentication_returns_unauthorized(): void
    {
        // Không đăng nhập

        // Tạo branch
        $branch = Branch::factory()->create(['status' => 'active']);

        // Dữ liệu order
        $orderData = [
            'branch_id' => $branch->id,
            'address' => '123 Test Street',
            'payment_method' => 'Tiền mặt',
            'shipping_fee' => 20000,
            'order_details' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                    'total_price' => 100000,
                ],
            ],
        ];

        // Gọi API
        $response = $this->postJson('/api/orders/create', $orderData);

        // Kiểm tra response
        $response->assertStatus(401);
    }
}

