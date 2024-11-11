<?php

namespace App\Repositories\Product;

interface ProductDetailRepositoryInterface {
    public function store(array $productDetail);
    public function getAll();
    public function getByProductId($id);
    public function getByBranchId($id);
    public function getByProductAndBranchId($productId, $branchId);
    public function getByThreeIds($productId, $branchId, $colorId);
    public function update(array $data, $productId, $branchId, $colorId);
    public function updateEach(array $data, $productDetail, $attributeOfproductDetail);
    public function delete($id);
}
