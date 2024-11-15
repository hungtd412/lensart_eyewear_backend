<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CartAndDetailSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $this->seedCarts();
    }

    public function seedCarts() {
        $faker = Faker::create();
        $users = User::where('role_id', 3)->get();

        //create cart for each user
        foreach ($users as $user) {
            $cartId = DB::table('carts')->insertGetId([
                'user_id' => $user->id,
            ]);

            $this->seedCartDetails($cartId, $faker);
        }
    }

    public function seedCartDetails($cartId, $faker) {

        $numberOfItems = $faker->numberBetween(0, 3);
        $totalPrice = 0;

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $productDetail = $this->getRandomProductDetails();
            $quantity = $faker->numberBetween(1, $productDetail['quantity'] - 1);
            DB::table('cart_details')->insertOrIgnore([
                [
                    'cart_id' => $cartId,
                    'product_id' => $productDetail['product_id'], // HCM
                    'branch_id' => $productDetail['branch_id'],
                    'color' => $productDetail['color'],
                    'quantity' => $quantity,
                    'total_price' => $quantity * $productDetail->price,
                ]
            ]);

            $totalPrice += $quantity * $productDetail->price;
        }

        $this->updateTotalPriceForCart($cartId, $totalPrice);
    }

    public function getRandomProductDetails() {
        $productDetail = ProductDetail::inRandomOrder()->where('quantity', '>', 0)->first();
        $productDetail->price = $this->getPriceByProducId($productDetail->product_id);
        return $productDetail;
    }

    public function getPriceByProducId($productId) {
        return Product::select('price')->where('id', $productId)->first()->price;
    }

    public function updateTotalPriceForCart($cartId, $totalPrice) {
        DB::table('carts')
            ->where('id', $cartId)
            ->update(['total_price' => $totalPrice]);
    }
}
