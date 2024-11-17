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
        $cartDetail = $this->cartDetailRepository->store($data);

        return response()->json([
            'status' => 'success',
            'cart' => $cartDetail
        ], 200);
    }

    public function update($data, $id) {
        $cartDetail = $this->cartDetailRepository->getById($id);

        $this->cartDetailRepository->update($data, $cartDetail);

        return response()->json([
            'message' => 'success',
            'carts' => $cartDetail
        ], 200);
    }
}
