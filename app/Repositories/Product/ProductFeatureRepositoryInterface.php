<?php

namespace App\Repositories\Product;

interface ProductFeatureRepositoryInterface
{
    public function store(array $productFeature);
    public function getAll();
    public function getById($id);
    public function getByProductId($id);
    public function update(array $data, $productFeature);
    public function deleteByProductId($id);
    public function delete($productFeature);

    public function getAllActive();

    public function getByIdActive($id);

    public function getByProductIdActive($id);
}
