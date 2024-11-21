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

    public function getAllCartDetails($userId)
    {
        return $this->cartDetailRepository->getAllCartDetails($userId);
    }

    public function store(array $data)
    {
        $userId = $data['user_id'];
        $cart = $this->cartDetailRepository->getCartByUserId($userId); // Lấy cart theo user_id

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $data['cart_id'] = $cart->id; // Thêm cart_id vào dữ liệu
        return $this->cartDetailRepository->store($data);
    }

    public function update(array $data, $id, $userId)
    {
        $cartDetail = $this->cartDetailRepository->getByIdAndUser($id, $userId); // Kiểm tra userId với cartDetail

        if (!$cartDetail) {
            return response()->json(['message' => 'Cart detail not found or unauthorized'], 404);
        }

        return $this->cartDetailRepository->update($data, $cartDetail);
    }

    public function delete($cartDetailId, $userId)
    {
        $cartDetail = $this->cartDetailRepository->getByIdAndUser($cartDetailId, $userId);

        if (!$cartDetail) {
            return response()->json(['message' => 'Cart detail not found or unauthorized'], 404);
        }

        $this->cartDetailRepository->delete($cartDetailId);

        return response()->json(['message' => 'Item deleted successfully'], 200);
    }


    public function clearCart($cartId, $userId)
    {
        $cart = $this->cartDetailRepository->getCartByIdAndUser($cartId, $userId); // Kiểm tra userId với cart

        if (!$cart) {
            return response()->json(['message' => 'Cart not found or unauthorized'], 404);
        }

        $this->cartDetailRepository->clearCart($cartId);

        return response()->json(['message' => 'Cart cleared successfully'], 200);
    }

    public function calculateTotalWithCoupon($userId, array $selectedIds, $couponCode = null)
    {
        // Lấy cart của user
        $cart = $this->cartDetailRepository->getCartByUserId($userId);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return $this->cartDetailRepository->calculateTotalWithCoupon($selectedIds, $couponCode);
    }

    public function quickBuy(array $data)
    {
        $userId = $data['user_id'];

        // Get or create the cart for the user
        $cart = $this->cartDetailRepository->getOrCreateCart($userId);
        $data['cart_id'] = $cart->id;

        // Add or update the product in cart details
        $cartDetail = $this->cartDetailRepository->addOrUpdateCartDetail(
            $data['cart_id'],
            $data['product_id'],
            [
                'branch_id' => $data['branch_id'],
                'color' => $data['color'],
                'quantity' => $data['quantity'],
            ]
        );

        // Recalculate the total price for the cart detail
        // $this->cartDetailRepository->updateCartDetailTotalPrice($cartDetail);

        // Return updated cart detail with total price
        return [
            'cart_detail' => $cartDetail,
            'total_price' => $cartDetail->total_price,
        ];
    }
}
