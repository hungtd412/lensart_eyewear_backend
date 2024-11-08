<?php

namespace App\Repositories\Product;

interface ProductRepositoryInterface {
    public function store(array $product);
    public function getAll();
    public function getById(array $id);
    public function update(array $data, $product);
    public function updateEach(array $data, $product, $attributeOfProduct);
    // public function updateDescription(array $data, $product);
    // public function updateBrand(array $data, $product);
    // public function updateCategory(array $data, $product);
    // public function updateColor(array $data, $product);
    // public function updateMaterial(array $data, $product);
    // public function updateShape(array $data, $product);
    // public function updateGender(array $data, $product);
    // public function updateCreatedTime(array $data, $product);
    public function switchStatus($product);
}
