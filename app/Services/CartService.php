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

    public function store(array $data) {
        $cart = $this->cartRepository->store($data);

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ], 200);
    }

    public function update($data, $id) {
        $carts = $this->cartRepository->getById($id);

        $this->cartRepository->update($data, $carts);

        return response()->json([
            'message' => 'success',
            'carts' => $carts
        ], 200);
    }
}
