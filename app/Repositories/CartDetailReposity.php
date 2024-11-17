<?php

namespace App\Repositories;


use App\Models\CartDetail;
use App\Models\Cart;
use Illuminate\Support\Collection;
use App\Repositories\CartDetailReposityInterface;

class CartDetailReposity implements CartDetailReposityInterface {
    public function getAllCartDetails(): Collection
    {
        return CartDetail::all();
    }

    public function store(array $cartDetail): CartDetail {
        return CartDetail::create($cartDetail);
    }

    public function getById($id) {
        return CartDetail::find($id);
    }

    public function update(array $data, $cartDetail) {
        // Cập nhật số lượng cho `cartDetail`
        $cartDetail->quantity = $data['quantity'] ?? $cartDetail->quantity;
        $cartDetail->save();

        // Cập nhật lại `total_price` cho `cartDetail`
        $this->updateCartDetailTotalPrice($cartDetail);

        // Cập nhật lại `total_price` cho `cart`
        $this->updateCartTotalPrice($cartDetail->cart_id);

        return $cartDetail;
    }

    private function updateCartDetailTotalPrice($cartDetail) {
        $product = $cartDetail->product;
        $branch = $cartDetail->branch;

        if ($product && $branch) {
            // Tính lại `total_price`
            $cartDetail->total_price = $cartDetail->quantity * $product->price * $branch->index;
            $cartDetail->save();
        }
    }

    private function updateCartTotalPrice($cartId) {
        $totalPrice = CartDetail::where('cart_id', $cartId)->sum('total_price');
        $cart = Cart::find($cartId);
        if ($cart) {
            $cart->total_price = $totalPrice;
            $cart->save();
        }
    }

    // Xóa một mục trong giỏ hàng
    public function delete($cartDetailId) {
        $cartDetail = CartDetail::find($cartDetailId);

        if ($cartDetail) {
            $cartId = $cartDetail->cart_id;
            $cartDetail->delete();

            // Cập nhật lại `total_price` cho `cart`
            $this->updateCartTotalPrice($cartId);
        }
    }

    // Xóa tất cả các mục trong giỏ hàng theo `cart_id`
    public function clearCart($cartId) {
        CartDetail::where('cart_id', $cartId)->delete();

        // Cập nhật lại `total_price` cho `cart`
        $cart = Cart::find($cartId);
        if ($cart) {
            $cart->total_price = 0;
            $cart->save();
        }
    }

}
