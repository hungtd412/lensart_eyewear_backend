<?php

namespace App\Repositories;
use Illuminate\Support\Collection;

interface CartReposityInterface {
    public function getAllCarts(): Collection;

    public function store(array $data);

    public function getById(array $carts);
    public function update(array $data, $carts);
}
