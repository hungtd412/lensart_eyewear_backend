<?php

namespace App\Repositories\Product;

use App\Models\ProductImage;

class ProductImageRepository implements ProductImageRepositoryInterface {
    public function store(array $productImage): ProductImage {
        return ProductImage::create($productImage);
    }

    public function getAll() {
        return ProductImage::all()->groupBy('product_id');
    }

    public function getById($id) {
        return ProductImage::find($id);
    }

    public function getByProductId($productId) {
        return ProductImage::where('product_id', $productId)
            ->get();
    }

    public function update(array $data, $productImage) {
        $productImage->update($data);
    }

    public function delete($productImage) {
        $productImage->delete();
    }
}
