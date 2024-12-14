<?php

namespace App\Repositories\Product;

interface ProductImageRepositoryInterface
{
    public function store(array $productImage);
    public function getAll();
    public function getById(array $id);
    public function getByProductId(array $id);
    public function update(array $data, $productImage);
    public function delete($productImage);

    public function getAllActive();

    public function getByIdActive($id);

    public function getByProductIdActive($id);
}
