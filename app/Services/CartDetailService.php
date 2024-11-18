<?php

namespace App\Services;

use App\Repositories\CartDetailReposityInterface;
use Illuminate\Support\Collection;

class CartDetailService
{
    protected $cartDetailRepository;

    public function __construct(CartDetailReposityInterface $cartDetailRepository)
    {
        $this->cartDetailRepository = $cartDetailRepository;
    }

    public function getAllCartDetails(): Collection
    {
        return $this->cartDetailRepository->getAllCartDetails();
    }

    public function store(array $data)
    {
        $cartDetail = $this->cartDetailRepository->store($data);

        return response()->json([
            'status' => 'success',
            'cart' => $cartDetail
        ], 200);
    }

    public function update($data, $id)
    {
        $cartDetail = $this->cartDetailRepository->getById($id);

        $this->cartDetailRepository->update($data, $cartDetail);

        return response()->json([
            'message' => 'success',
            'carts' => $cartDetail
        ], 200);
    }

    public function delete($cartDetailId)
    {
        // Xóa mục giỏ hàng
        $this->cartDetailRepository->delete($cartDetailId);

        return response()->json([
            'message' => 'Item deleted successfully',
            'cart_detail_id' => $cartDetailId
        ], 200);
    }


    public function clearCart($cartId)
    {
        // Xóa tất cả các mục giỏ hàng liên quan đến `cartId`
        $this->cartDetailRepository->clearCart($cartId);

        return response()->json([
            'message' => 'Cart cleared successfully',
            'cart_id' => $cartId
        ], 200);
    }

    public function calculateTotalWithCoupon(array $selectedIds, $couponCode = null)
    {
        return $this->cartDetailRepository->calculateTotalWithCoupon($selectedIds, $couponCode);
    }
}
