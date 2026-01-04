<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'brand_id' => 1,
            'category_id' => 1,
            'shape_id' => 1,
            'material_id' => 1,
            'gender' => fake()->randomElement(['male', 'female', 'unisex']),
            'price' => fake()->randomFloat(2, 100, 1000),
            'offer_price' => null,
            'created_time' => now(),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the product has an offer price.
     */
    public function withOfferPrice(): static
    {
        return $this->state(fn (array $attributes) => [
            'offer_price' => fake()->randomFloat(2, 50, $attributes['price'] * 0.9),
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}

