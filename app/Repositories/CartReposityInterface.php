<?php

namespace App\Repositories;
use Illuminate\Support\Collection;

interface CartReposityInterface {
    public function getAllCarts(): Collection;

    public function store(array $data);
}
