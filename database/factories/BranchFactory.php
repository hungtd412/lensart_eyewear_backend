<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Sử dụng manager user đã được tạo trong test setup hoặc tạo mới
        $manager = \App\Models\User::where('email', 'test-manager@example.com')->first();
        
        if (!$manager) {
            // Fallback: tạo manager nếu chưa có
            $manager = \App\Models\User::factory()->create(['role_id' => 2]);
        }

        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'manager_id' => $manager->id,
            'index' => fake()->randomFloat(2, 0.8, 1.2),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the branch is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}

