<?php

namespace App\Repositories\Product;

interface ProductDetailRepositoryInterface {
    //'product_id', 'branch_id', 'quantity', 'price'
    public function store(array $productDetail);
    public function getAll();
    public function getById($id);
    public function getByProductId($id);
    public function getByBranchId($id);
    public function getByProductAndBranchId($productId, $branchId);
    public function update(array $data, $productDetail);
    public function updateEach(array $data, $productDetail, $attributeOfproductDetail);
}
