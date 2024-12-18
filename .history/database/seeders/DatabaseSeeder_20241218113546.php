<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            AttributesProductSeeder::class,
            BranchSeeder::class,
            ProductAndDetailSeeder::class,
            ProductFeatureSeeder::class,
            CouponSeeder::class,
            CartAndDetailSeeder::class,
            OrderAndDetailSeeder::class,
            WishlistAndDetailSeeder::class,
            BlogSeeder::class,
            ProductReviewSeeder::class,
            ProductImageSeeder::class, tam thoi chua seed product images
        ]);
    }
}
