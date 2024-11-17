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
}
