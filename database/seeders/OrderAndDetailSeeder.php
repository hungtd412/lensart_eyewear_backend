<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Coupon;
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

                $data = [
                    'user_id' => $user->id,
                    'date' => Carbon::now(),
                    'branch_id' => $branch->id,
                    'address' => $user->address,
                    'note' => $this->getRandomNote(),
                    'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                    'order_status' => $orderStatuses[array_rand($orderStatuses)],
                ];

                $coupon = $this->addCouponForOrderOrNot($data);

                $orderId = DB::table('orders')->insertGetId($data);

                if (is_null($coupon)) {
                    $this->seedOrderDetails($orderId, $branch->id, '');
                } else {
                    $this->seedOrderDetails($orderId, $branch->id, $coupon->code);
                }
            }
        }
    }

    protected function seedOrderDetails($orderId, $branchId, $couponCode) {
        $productDetails = ProductDetail::where('branch_id', $branchId)
            ->where('quantity', '>', 0)
            ->take(rand(1, 5))->get();
        $totalPrice = 0;

        foreach ($productDetails as $productDetail) {
            $quantity = rand(1, $productDetail->quantity);
            $price = $this->getPriceByProductId($productDetail->product_id);
            $totalPrice += $price * $quantity;

            DB::table('order_details')->insert([
                'order_id' => $orderId,
                'product_id' => $productDetail->product_id,
                'color' => $productDetail->color,
                'quantity' => $quantity,
                'total_price' => $price * $quantity,
            ]);
        }

        $this->updateTotalPriceForOrder($orderId, $totalPrice, $couponCode);
    }

    public function getRandomProductDetails() {
        $productDetail = ProductDetail::inRandomOrder()->where('quantity', '>', 0)->first();
        $productDetail->price = $this->getPriceByProductId($productDetail->product_id);
        return $productDetail;
    }

    public function getRandomNote() {
        return \Faker\Factory::create()->randomElement([
            'Che tên dùm em ạ',
            'Giao buổi sáng cho em',
            'Anh chị shipper cứ để hàng trên lan can cho em',
        ]);
    }

    public function getPriceByProductId($productId) {
        return Product::select('price')->where('id', $productId)->first()->price;
    }

    public function updateTotalPriceForOrder($orderId, $totalPrice, $couponCode) {
        $coupon = Coupon::where('code', $couponCode)->first();

        if ($coupon) {
            $discount_price = $coupon->discount_price;
        } else {
            $discount_price = 0;
        }

        DB::table('orders')->where('id', $orderId)->update(['total_price' => $totalPrice - $discount_price]);
    }

    public function addCouponForOrderOrNot(&$data) {
        $coupon = rand(0, 1) ? Coupon::inRandomOrder()->first() : null;
        if (!is_null($coupon)) {
            $data['coupon_id'] = $coupon->id;
        }
        return $coupon;
    }
}
