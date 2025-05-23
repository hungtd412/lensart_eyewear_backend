<?php

namespace App\Repositories\Product;

interface ProductDetailRepositoryInterface {
    public function store(array $productDetail);
    public function getById($id);
    public function getAll();
    public function getByProductId($id);
    public function getByBranchId($id);
    public function getByProductAndBranchId($productId, $branchId);
    public function getByThreeIds($productId, $branchId, $color);
    public function update(array $data, $productId, $branchId, $color);
    public function isEnoughQuantity($productId, $branchId, $color, $quantity);
    public function decreaseQuantityByThreeIds($productId, $branchId, $color, $quantity);
    public function updateEach(array $data, $productDetail, $attributeOfproductDetail);
    public function delete($id);
    public function switchStatus($id);

    public function getAllActive();

    public function getByProductIdActive($id);

    public function getByBranchIdActive($id);

    public function getByProductAndBranchIdActive($productId, $branchId);

    public function isExistProductId($productId);
    public function isExistVariant($productId, $branchId, $color);
}
