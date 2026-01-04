<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Branch;
use App\Models\ProductDetail;
use App\Models\CartDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartDetailControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRequiredData();
    }

    /**
     * Tạo dữ liệu cần thiết cho test (role, category, brand, material, shape, manager user)
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

        // Tạo manager user cho branch nếu chưa có
        if (!User::where('email', 'test-manager@example.com')->exists()) {
            User::factory()->create([
                'email' => 'test-manager@example.com',
                'role_id' => 2, // Manager role
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

        // Tạo brand nếu chưa có (tên bảng là brands)
        if (!DB::table('brands')->where('id', 1)->exists()) {
            DB::table('brands')->insert([
                ['id' => 1, 'name' => 'Brand 1'],
                ['id' => 2, 'name' => 'Brand 2'],
                ['id' => 3, 'name' => 'Brand 3'],
            ]);
        }

        // Tạo material nếu chưa có (tên bảng là materials)
        if (!DB::table('materials')->where('id', 1)->exists()) {
            DB::table('materials')->insert([
                ['id' => 1, 'name' => 'Material 1'],
                ['id' => 2, 'name' => 'Material 2'],
                ['id' => 3, 'name' => 'Material 3'],
            ]);
        }

        // Tạo shape nếu chưa có (tên bảng là shapes)
        if (!DB::table('shapes')->where('id', 1)->exists()) {
            DB::table('shapes')->insert([
                ['id' => 1, 'name' => 'Shape 1'],
                ['id' => 2, 'name' => 'Shape 2'],
                ['id' => 3, 'name' => 'Shape 3'],
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
     * Test thêm sản phẩm mới vào giỏ hàng thành công
     */
    public function test_add_new_product_to_cart_successfully(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo cart cho user
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

        // Dữ liệu request
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 2,
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'cart_id',
                    'product_id',
                    'branch_id',
                    'color',
                    'quantity',
                    'total_price',
                ],
            ])
            ->assertJson([
                'status' => 'success',
            ]);

        // Kiểm tra database
        $this->assertDatabaseHas('cart_details', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 2,
        ]);
    }

    /**
     * Test thêm sản phẩm đã tồn tại vào giỏ hàng (cập nhật số lượng)
     */
    public function test_add_existing_product_to_cart_updates_quantity(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo cart cho user
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        // Tạo product, branch và product detail
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'red',
            'quantity' => 20,
            'status' => 'active',
        ]);

        // Tạo cart detail đã tồn tại
        $existingCartDetail = CartDetail::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'red',
            'quantity' => 3,
        ]);

        // Dữ liệu request
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'red',
            'quantity' => 2,
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);

        // Kiểm tra số lượng đã được cập nhật (3 + 2 = 5)
        $this->assertDatabaseHas('cart_details', [
            'id' => $existingCartDetail->id,
            'quantity' => 5,
        ]);
    }

    /**
     * Test thêm sản phẩm khi giỏ hàng không tồn tại
     */
    public function test_add_product_when_cart_not_exists_returns_error(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Không tạo cart cho user

        // Tạo product, branch và product detail
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'blue',
            'quantity' => 10,
            'status' => 'active',
        ]);

        // Dữ liệu request
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'blue',
            'quantity' => 2,
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra response
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Cart not found',
            ]);
    }

    /**
     * Test thêm sản phẩm với số lượng vượt quá tồn kho
     */
    public function test_add_product_with_insufficient_stock_returns_error(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo cart cho user
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        // Tạo product, branch và product detail với số lượng thấp
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'green',
            'quantity' => 5, // Chỉ có 5 sản phẩm
            'status' => 'active',
        ]);

        // Dữ liệu request với số lượng lớn hơn tồn kho
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'green',
            'quantity' => 10, // Yêu cầu 10 sản phẩm
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra response - sẽ trả về 500 hoặc exception message
        $response->assertStatus(500);
    }

    /**
     * Test validation khi thiếu các trường bắt buộc
     */
    public function test_add_product_with_missing_required_fields_fails_validation(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Dữ liệu request thiếu các trường bắt buộc
        $requestData = [
            // Thiếu product_id, branch_id, quantity
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra validation errors (format từ FailedValidationTrait)
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code'])
            ->assertJson([
                'status_code' => 422,
            ]);
        
        $errorData = $response->json('error');
        $this->assertArrayHasKey('product_id', $errorData);
        $this->assertArrayHasKey('branch_id', $errorData);
        $this->assertArrayHasKey('quantity', $errorData);
    }

    /**
     * Test validation khi product_id không tồn tại
     */
    public function test_add_product_with_invalid_product_id_fails_validation(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Dữ liệu request với product_id không tồn tại
        $requestData = [
            'product_id' => 99999, // ID không tồn tại
            'branch_id' => 1,
            'quantity' => 1,
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra validation errors (format từ FailedValidationTrait)
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);
        
        $errorData = $response->json('error');
        $this->assertArrayHasKey('product_id', $errorData);
    }

    /**
     * Test validation khi branch_id không tồn tại
     */
    public function test_add_product_with_invalid_branch_id_fails_validation(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product
        $product = Product::factory()->create(['status' => 'active']);

        // Dữ liệu request với branch_id không tồn tại
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => 99999, // ID không tồn tại
            'quantity' => 1,
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra validation errors (format từ FailedValidationTrait)
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);
        
        $errorData = $response->json('error');
        $this->assertArrayHasKey('branch_id', $errorData);
    }

    /**
     * Test validation khi quantity không hợp lệ
     */
    public function test_add_product_with_invalid_quantity_fails_validation(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo product và branch
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);

        // Dữ liệu request với quantity = 0 (không hợp lệ)
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'quantity' => 0, // Phải >= 1
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra validation errors (format từ FailedValidationTrait)
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);
        
        $errorData = $response->json('error');
        $this->assertArrayHasKey('quantity', $errorData);
    }

    /**
     * Test thêm sản phẩm khi chưa đăng nhập (unauthorized)
     */
    public function test_add_product_without_authentication_returns_unauthorized(): void
    {
        // Không đăng nhập

        // Tạo product và branch
        $product = Product::factory()->create(['status' => 'active']);
        $branch = Branch::factory()->create(['status' => 'active']);

        // Dữ liệu request
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'quantity' => 1,
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra response
        $response->assertStatus(401);
    }

    /**
     * Test thêm sản phẩm với color không tồn tại trong product_details
     */
    public function test_add_product_with_invalid_color_fails_validation(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo cart cho user
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

        // Dữ liệu request với color không tồn tại
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'invalid-color', // Color không tồn tại
            'quantity' => 1,
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra validation errors (format từ FailedValidationTrait)
        $response->assertStatus(422)
            ->assertJsonStructure(['error', 'status_code']);
        
        $errorData = $response->json('error');
        $this->assertArrayHasKey('color', $errorData);
    }

    /**
     * Test thêm sản phẩm với product inactive
     */
    public function test_add_inactive_product_returns_error(): void
    {
        // Tạo user và đăng nhập
        $user = $this->createCustomerUser();
        Sanctum::actingAs($user);

        // Tạo cart cho user
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        // Tạo product với status inactive
        $product = Product::factory()->inactive()->create();
        $branch = Branch::factory()->create(['status' => 'active']);
        $productDetail = ProductDetail::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 10,
            'status' => 'active',
        ]);

        // Dữ liệu request
        $requestData = [
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'color' => 'black',
            'quantity' => 2,
        ];

        // Gọi API
        $response = $this->postJson('/api/cart_details/create', $requestData);

        // Kiểm tra response - sẽ trả về 500 hoặc exception
        $response->assertStatus(500);
    }
}

