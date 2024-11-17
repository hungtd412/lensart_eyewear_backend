<?php

namespace App\Services;

use App\Repositories\CartReposityInterface;
use Illuminate\Support\Collection;

class CartService {
    protected $cartRepository;

    public function __construct(CartReposityInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getAllCarts(): Collection
    {
        return $this->cartRepository->getAllCarts();
    }

    public function store($data) {
        $cart = $this->cartRepository->store($data);

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ], 200);
    }
}
