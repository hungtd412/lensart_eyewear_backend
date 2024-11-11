<?php

namespace App\Repositories\Product;

interface BrandRepositoryInterface {
    public function store(array $brand);
    public function getAll();
    public function getById(array $brand);
    public function update(array $data, $brand);
    public function switchStatus($brand);
}
