<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface CartDetailReposityInterface
{
    public function getAllCartDetails($userId);

    public function getCartByUserId($userId);

    public function getByIdAndUser($id, $userId);

    public function getCartByIdAndUser($cartId, $userId);

    public function store(array $data);

    public function getById(array $cartDetail);
    public function update(array $data, $cartDetail);

    public function delete($cartDetailId);

    public function clearCart($cartId);

    public function calculateTotalWithCoupon(array $selectedCartDetailIds, $couponCode = null);
}
