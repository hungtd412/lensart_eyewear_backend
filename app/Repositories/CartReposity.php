<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartDetail;
use App\Repositories\CartReposityInterface;

class CartReposity implements CartReposityInterface {

    public function addToCart(array $data)
    {
        // Tìm hoặc tạo giỏ hàng cho người dùng
        $cart = Cart::firstOrCreate(['user_id' => $data['user_id']], ['total_price' => 0]);

        // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $data['product_id'])
            ->where('branch_id', $data['branch_id'])
            ->where('color', $data['color'])
            ->first();

        $product = Product::findOrFail($data['product_id']);

        if ($cartDetail) {
            // Cập nhật số lượng nếu sản phẩm đã tồn tại
            $cartDetail->quantity += $data['quantity'];
            $cartDetail->total_price += $product->offer_price * $data['quantity'];
            $cartDetail->save();
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            CartDetail::create([
                'cart_id' => $cart->id,
                'product_id' => $data['product_id'],
                'branch_id' => $data['branch_id'],
                'color' => $data['color'],
                'quantity' => $data['quantity'],
                'total_price' => $product->offer_price * $data['quantity'],
            ]);
        }

        // Cập nhật tổng giá trị giỏ hàng
        $cart->total_price += $product->offer_price * $data['quantity'];
        $cart->save();

        return $cart;
    }

    public function getCart(int $userId)
    {
        return Cart::with('details.product')
            ->where('user_id', $userId)
            ->first();
    }
}
