<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'branch_id' => Branch::factory(),
            'date' => now(),
            'address' => fake()->address(),
            'note' => fake()->optional()->sentence(),
            'coupon_id' => null,
            'total_price' => fake()->randomFloat(2, 100000, 1000000),
            'order_status' => 'Đang xử lý',
            'payment_status' => 'Chưa thanh toán',
            'payment_method' => fake()->randomElement(['Tiền mặt', 'Chuyển khoản']),
            'status' => 'active',
        ];
    }
}

