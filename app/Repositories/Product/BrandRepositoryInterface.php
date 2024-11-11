<?php

namespace App\Repositories\Product;

interface BrandRepositoryInterface {
    public function store(array $brand);
    public function getAll();
    public function getById($id);
    public function update(array $data, $brand);
    public function switchStatus($brand);
}
