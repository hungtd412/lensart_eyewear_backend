<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductAndDetailSeeder extends Seeder {
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
                'material_id' => $faker->numberBetween(1, 3),
                'shape_id' => $faker->numberBetween(1, 3),
                'gender' => $faker->randomElement(['male', 'female', 'unisex']),
                'price' => $faker->numberBetween(200000, 1000000),
                'created_time' => Carbon::now(),
            ]);

            $this->seedProductDetails($productId, $faker);
        }
    }

    public function seedProductDetails($productId, $faker) {
        $numbersOfVariant = $faker->numberBetween(1, 3);
        $used_color = [];
        $color = 'Đỏ';
        for ($i = 1; $i <= $numbersOfVariant; $i++) {
            do {
                $color = $this->getRandomColor();
            } while (in_array($color, $used_color));
            array_push($used_color, $color);
            DB::table('product_details')->insert([
                [
                    'product_id' => $productId,
                    'branch_id' => 1, // HCM
                    'color' => $color,
                ],
                [
                    'product_id' => $productId,
                    'branch_id' => 2, // DN
                    'color' => $color,
                ],
                [
                    'product_id' => $productId,
                    'branch_id' => 3, // HN
                    'color' => $color,
                ]
            ]);
        }
    }

    public function getIndexByBranchId($branchId) {
        return DB::table('product_details')
            ->where('branch_id', $branchId)
            ->pluck('index');
    }

    public function getRandomColor() {
        return Faker::create()->randomElement(['Đỏ', 'Đen', 'Xám', 'Hồng']);
    }
}
