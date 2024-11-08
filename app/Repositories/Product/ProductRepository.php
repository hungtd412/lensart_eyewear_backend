<?php

namespace App\Repositories\Product;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface {
    public function store(array $product): Product {
        return Product::create($product);
    }

    public function getAll() {
        return Product::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Product::find($id);
    }

    public function update(array $data, $product) {
        $product->update($data);
    }

    public function updateEach(array $data, $product, $attributeOfProduct) {
        $product->$attributeOfProduct = $data[$attributeOfProduct];
        $product->save();
    }

    public function switchStatus($product) {
        $product->status = $product->status == 'active' ? 'inactive' : 'active';
        $product->save();
    }
}
