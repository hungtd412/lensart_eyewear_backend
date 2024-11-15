<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductDetailRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Models\Product;

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
            'products' => $products
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

    // Lọc Gọng kính
    public function filterFrames($request)
    {
        $query = Product::query();

        $query->where('status', 'active');

        if ($request->has('shape') && !empty($request->input('shape'))) {
            $query = $this->productRepository->filterByShape($query, $request->input('shape'));
        }

        if ($request->has('gender') && !empty($request->input('gender'))) {
            $query = $this->productRepository->filterByGender($query, $request->input('gender'));
        }

        if ($request->has('material') && !empty($request->input('material'))) {
            $query = $this->productRepository->filterByMaterial($query, $request->input('material'));
        }

        if ($request->has('price_range') && !empty($request->input('price_range'))) {
            $query = $this->productRepository->filterByPriceRange($query, $request->input('price_range'));
        }

        return $query->distinct()->get();
    }

    // Lọc Tròng kính
    public function filterLenses($request)
    {
        $query = Product::query();

        $query->where('status', 'active');

        if ($request->has('brand') && !empty($request->input('brand'))) {
            $query = $this->productRepository->filterByBrand($query, $request->input('brand'));
        }

        if ($request->has('features') && !empty($request->input('features'))) {
            $query = $this->productRepository->filterByFeatures($query, $request->input('features'));
        }

        if ($request->has('price_range') && !empty($request->input('price_range'))) {
            $query = $this->productRepository->filterByPriceRange($query, $request->input('price_range'));
        }

        return $query->distinct()->get();
    }

    public function getBestSellingProducts($limit = 10)
    {
        return $this->productRepository->getBestSellingProducts($limit);
    }

    public function getNewestProducts($limit = 10)
    {
        return $this->productRepository->getNewestProducts($limit);
    }

}
