<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductFeatureSeeder extends Seeder {
    public function run() {
        $faker = Faker::create();

        // Get all feature IDs
        $featureIds = DB::table('features')->pluck('id')->toArray();

        // Loop through each product
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            // Select 1–2 random feature IDs for each product
            $randomFeatureIds = $faker->randomElements($featureIds, $faker->numberBetween(1, 2));

            // Insert each selected feature into product_features
            foreach ($randomFeatureIds as $featureId) {
                DB::table('product_features')->insert([
                    'product_id' => $product->id,
                    'feature_id' => $featureId,
                ]);
            }
        }
    }
}
