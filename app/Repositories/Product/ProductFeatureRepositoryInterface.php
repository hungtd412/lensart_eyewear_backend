<?php

namespace App\Repositories\Product;

interface ProductFeatureRepositoryInterface {
    public function store(array $productFeature);
    public function getAll();
    public function getById(array $id);
    public function getByProductId(array $id);
    public function update(array $data, $productFeature);
    public function delete($productFeature);
}
