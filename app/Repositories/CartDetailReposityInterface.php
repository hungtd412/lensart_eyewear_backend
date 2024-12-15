<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Models\CartDetail;

interface CartDetailReposityInterface
{
    public function getAllCartDetails($userId);

    public function getCartByUserId($userId);

    public function getByIdAndUser($id, $userId);

    public function getCartByIdAndUser($cartId, $userId);

    public function store(array $data);

    public function updateCartDetailTotalPrice(CartDetail $cartDetail);

    public function getById(array $cartDetail);
    public function update(array $data, $cartDetail);

    public function delete($cartDetailId);

    public function clearCart($cartId);

    public function calculateTotalWithCoupon(array $selectedCartDetailIds, $couponCode = null);

    public function getOrCreateCart($userId);

    public function addOrUpdateCartDetail($cartId, $productId, array $attributes);

    public function calculateTotalQuantity($userId);
}
