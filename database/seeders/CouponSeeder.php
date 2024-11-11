<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CouponSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();

        $this->seedCoupons(10, $faker);
    }

    public function seedCoupons($numberOfCoupons, $faker) {
        for ($i = 1; $i <= $numberOfCoupons; $i++) {
            $price = $this->getRandomPrice($faker);
            DB::table('coupons')->insert([
                'name' => 'Coupon ' . ($price / 1000) . 'k',
                'code' => 'coupon' . ($price / 1000) . 'k' . $i,
                'quantity' => $faker->numberBetween(1, 10),
                'discount_price' => $price,
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }

    public function getRandomPrice($faker) {
        $prices = [50000, 100000, 150000, 160000, 500000];
        return $faker->randomElement($prices);
    }
}
