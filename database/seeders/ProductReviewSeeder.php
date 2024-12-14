<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductReview;
use Faker\Factory as Faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $products = Product::all();
        $users = User::all();

        foreach ($products as $product) {
            foreach ($users as $user) {
                ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'rating' => $faker->numberBetween(1, 5),
                    'review' => $faker->paragraph,
                    'status' => $faker->randomElement(['active', 'inactive']),
                ]);
            }
        }
    }
}
