<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Support\Collection;
use App\Repositories\CartReposityInterface;

class CartReposity implements CartReposityInterface {
    public function getAllCarts(): Collection
    {
        return Cart::all();
    }

    public function store(array $cart): Cart {
        return Cart::create($cart);
    }

    public function getById($id) {
        return Cart::find($id);
    }

    public function update(array $data, $cart) {
        $cart->update($data);
    }
}
