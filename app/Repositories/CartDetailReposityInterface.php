<?php

namespace App\Repositories;
use Illuminate\Support\Collection;

interface CartDetailReposityInterface {
    public function getAllCartDetails(): Collection;

    public function store(array $data);

    public function getById(array $cartDetail);
    public function update(array $data, $cartDetail);
}
