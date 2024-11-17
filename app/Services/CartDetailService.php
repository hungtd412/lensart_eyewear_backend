<?php

namespace App\Services;

use App\Repositories\CartDetailReposityInterface;
use Illuminate\Support\Collection;

class CartDetailService {
    protected $cartDetailRepository;

    public function __construct(CartDetailReposityInterface $cartDetailRepository)
    {
        $this->cartDetailRepository = $cartDetailRepository;
    }

    public function getAllCartDetails(): Collection
    {
        return $this->cartDetailRepository->getAllCartDetails();
    }

    public function store(array $data) {
        $cart = $this->cartDetailRepository->store($data);

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ], 200);
    }
}
