<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();

        $this->seedProducts(10, $faker, 1);
        $this->seedProducts(25, $faker, 2);
        $this->seedProducts(20, $faker, 3);
    }

    public function getNameByType($type) {
        switch ($type) {
            case 1:
                return 'Tròng kính ';
            case 2:
                return 'Gọng kính ';
            case 3:
                return 'Kính râm ';
            default:
                return '';
        }
    }

    public function seedProducts($numberOfProducts, $faker, $type) {
        $name = $this->getNameByType($type);

        if ($name === '')
            return;

        for ($i = 1; $i <= $numberOfProducts; $i++) {
            $productId = DB::table('products')->insertGetId([
                'name' => $name . $i,
                'description' => $faker->sentence(15),
                'category_id' => $type,
                'brand_id' => $faker->numberBetween(1, 3),
                'color_id' => $faker->numberBetween(1, 3),
                'material_id' => $faker->numberBetween(1, 3),
                'shape_id' => $faker->numberBetween(1, 3),
                'gender' => $faker->randomElement(['male', 'female', 'unisex']),
                'created_time' => Carbon::now(),
            ]);

            $this->seedProductDetails($productId, $faker);
        }
    }

    public function seedProductDetails($productId, $faker) {
        $priceHCM = $faker->numberBetween(200000, 1000000);

        DB::table('product_details')->insert([
            [
                'product_id' => $productId,
                'branch_id' => 1, // HCM
                'quantity' => 0,
                'price' => $priceHCM,
            ],
            [
                'product_id' => $productId,
                'branch_id' => 2, // DN
                'quantity' => 0,
                'price' => $priceHCM * 0.8, // 80% of HCM price
            ],
            [
                'product_id' => $productId,
                'branch_id' => 3, // HN
                'quantity' => 0,
                'price' => $priceHCM, // Same price as HCM
            ]
        ]);
    }
}
