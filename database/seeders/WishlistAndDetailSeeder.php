<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;
use App\Models\WishlistDetail;
use App\Models\User;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class WishlistAndDetailSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $this->seedWishlists();
    }

    private function seedWishlists() {
        $faker = Faker::create();
        // Lấy tất cả các user có vai trò là khách hàng
        $users = User::where('role_id', 3)->get();

        // Tạo wishlist cho từng user
        foreach ($users as $user) {
            $wishlistId = DB::table('wishlists')->insertGetId([
                'user_id' => $user->id,
            ]);

            // Tạo dữ liệu cho wishlist_details
            $this->seedWishlistDetails($wishlistId, $faker);
        }
    }

    private function seedWishlistDetails($wishlistId, $faker) {
        // Số lượng sản phẩm yêu thích cho mỗi wishlist
        $numberOfItems = $faker->numberBetween(1, 5);

        for ($i = 0; $i < $numberOfItems; $i++) {
            $product = Product::inRandomOrder()->first();

            // Chèn dữ liệu vào bảng wishlist_details
            DB::table('wishlist_details')->insertOrIgnore([
                'wishlist_id' => $wishlistId,
                'product_id' => $product->id,
            ]);
        }
    }
}
