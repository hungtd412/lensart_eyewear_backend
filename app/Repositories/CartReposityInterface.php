<?php

namespace App\Repositories;

interface CartReposityInterface {
    public function addToCart(array $data);
    public function getCart(int $userId);
}
