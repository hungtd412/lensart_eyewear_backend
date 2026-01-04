<?php

namespace Database\Factories;

use App\Models\CartDetail;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartDetail>
 */
class CartDetailFactory extends Factory
{
    protected $model = CartDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'product_id' => Product::factory(),
            'branch_id' => Branch::factory(),
            'color' => fake()->colorName(),
            'quantity' => fake()->numberBetween(1, 5),
            'total_price' => 0,
        ];
    }
}

