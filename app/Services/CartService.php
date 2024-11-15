<?php

namespace App\Services;

use App\Repositories\CartReposityInterface;

class CartService {
    protected $cartRepository;

    public function __construct(CartReposityInterface $cartRepository) {
        $this->cartRepository = $cartRepository;
    }

    public function addToCart(array $data)
    {
        return $this->cartRepository->addToCart($data);
    }

    public function getCart(int $userId)
    {
        return $this->cartRepository->getCart($userId);
    }
}
