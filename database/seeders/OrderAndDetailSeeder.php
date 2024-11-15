<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Branch;
use App\Models\ProductDetail;

class OrderAndDetailSeeder extends Seeder {
    public function run() {
        $users = User::where('role_id', 3)->get();
        $orderStatuses = ['Đang xử lý', 'Đã xử lý và sẵn sàng giao hàng', 'Đang giao hàng', 'Đã giao', 'Đã hủy'];
        $paymentStatuses = ['Chưa thanh toán', 'Đã thanh toán'];


        foreach ($users as $user) {
            $orderCount = rand(0, 10);

            for ($i = 0; $i < $orderCount; $i++) {

                $branch = Branch::inRandomOrder()->first();

                $orderId = DB::table('orders')->insertGetId([
                    'user_id' => $user->id,
                    'date' => Carbon::now(),
                    'branch_id' => $branch->id,
                    'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                    'order_status' => $orderStatuses[array_rand($orderStatuses)],
                ]);

                $this->seedOrderDetails($orderId, $branch->id);
            }
        }
    }

    protected function seedOrderDetails($orderId, $branchId) {
        $productDetails = ProductDetail::where('branch_id', $branchId)
            ->where('quantity', '>', 0)
            ->take(rand(1, 5))->get();
        $totalPrice = 0;

        foreach ($productDetails as $productDetail) {
            $quantity = rand(1, $productDetail->quantity);
            $price = $this->getPriceById($productDetail->product_id);
            $totalPrice += $price * $quantity;

            DB::table('order_details')->insert([
                'order_id' => $orderId,
                'product_id' => $productDetail->product_id,
                'color' => $productDetail->color,
                'quantity' => $quantity,
                'total_price' => $price * $quantity,
            ]);
        }

        $this->updateTotalPriceForOrder($orderId, $totalPrice);
    }

    public function getRandomProductDetails() {
        $productDetail = ProductDetail::inRandomOrder()->where('quantity', '>', 0)->first();
        $productDetail->price = $this->getPriceById($productDetail->product_id);
        return $productDetail;
    }

    public function getPriceById($productId) {
        return Product::select('price')->where('id', $productId)->first()->price;
    }

    public function updateTotalPriceForOrder($orderId, $totalPrice) {
        DB::table('orders')
            ->where('id', $orderId)
            ->update(['total_price' => $totalPrice]);
    }
}
