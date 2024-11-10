<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductDetailRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductService {
    protected $productRepository;
    protected $productDetailRepository;

    public function __construct(ProductRepositoryInterface $productRepository, ProductDetailRepositoryInterface $productDetailRepository) {
        $this->productRepository = $productRepository;
        $this->productDetailRepository = $productDetailRepository;
    }

    public function store($data) {
        $product = $this->productRepository->store($data);

        $price = $product->price;
        $dataProductDetail = [
            'product_id' => $product->id,
            'branch_ids' => ['1', '2', '3'],
            'prices' => []
        ];

        // $this->productDetailRepository->store($data->toArray());

        return response()->json([
            'status' => 'success',
            'product' => $product
        ], 200);
    }

    public function getAll() {
        $products = $this->productRepository->getAll();

        return response()->json([
            'status' => 'success',
            'product' => $products
        ], 200);
    }

    public function getById($id) {
        $product = $this->productRepository->getById($id);
        // return gettype($product->);
        if ($product === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'product' => $product,
        ], 200);
    }

    public function update($data, $id) {
        $product = $this->productRepository->getById($id);

        $this->productRepository->update($data, $product);

        return response()->json([
            'message' => 'success',
            'product' => $product
        ], 200);
    }

    public function updateEach($data, $id, $attributeOfProduct) {
        $product = $this->productRepository->getById($id);

        $this->productRepository->updateEach($data->toArray(), $product, $attributeOfProduct);

        return response()->json([
            'message' => 'success',
            'product' => $product
        ], 200);
    }

    public function switchStatus($id) {
        $product = $this->productRepository->getById($id);

        $this->productRepository->switchStatus($product);

        return response()->json([
            'message' => 'success',
            'product' => $product
        ], 200);
    }
}
