<?php

namespace App\Repositories\Product;

use App\Models\ProductFeature;

class ProductFeatureRepository implements ProductFeatureRepositoryInterface {
    public function store(array $productFeature): ProductFeature {
        return ProductFeature::create($productFeature);
    }

    public function getAll() {
        return ProductFeature::all()->groupBy('product_id');
    }

    public function getById($id) {
        return ProductFeature::find($id);
    }

    public function getByProductId($productId) {
        return ProductFeature::where('product_id', $productId)
            ->get();
    }

    public function update(array $data, $productFeature) {
        $productFeature->update($data);
    }

    public function deleteByProductId($id) {
        ProductFeature::where('product_id', $id)->delete();
    }

    public function delete($productFeature) {
        $productFeature->delete();
    }
}
