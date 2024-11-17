<?php

namespace App\Repositories;

use App\Models\CartDetail;
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
}
