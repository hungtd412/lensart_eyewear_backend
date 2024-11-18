<?php

namespace App\Repositories;

interface WishlistRepositoryInterface
{
    public function getByUserId($userId);
    public function store(array $data);
    public function delete($wishlistDetailId);
    public function clearByUserId($userId);

    public function moveProductToCart($wishlistDetailId);
    public function moveAllToCart();
}
