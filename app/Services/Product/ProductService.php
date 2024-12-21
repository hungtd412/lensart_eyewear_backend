<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Models\Product;
use Carbon\Carbon;

class ProductService {
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function store($data) {
        $data['created_time'] = Carbon::now('Asia/Ho_Chi_Minh');
        $product = $this->productRepository->store($data);

        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 200);
    }

    public function getAll() {
        $products = $this->productRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $products
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
            'data' => $product,
        ], 200);
    }

    public function getByCategoryId($categoryId) {
        $products = $this->productRepository->getByCategoryId($categoryId);

        if ($products === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $products,
        ], 200);
    }

    public function update($data, $id) {
        $product = $this->productRepository->getById($id);

        $this->productRepository->update($data, $product);

        return response()->json([
            'message' => 'success',
            'data' => $product
        ], 200);
    }

    public function updateEach($data, $id, $attributeOfProduct) {
        $product = $this->productRepository->getById($id);

        $this->productRepository->updateEach($data->toArray(), $product, $attributeOfProduct);

        return response()->json([
            'message' => 'success',
            'data' => $product
        ], 200);
    }

    public function switchStatus($id) {
        $product = $this->productRepository->getById($id);

        $this->productRepository->switchStatus($product);

        return response()->json([
            'message' => 'success',
            'data' => $product
        ], 200);
    }

    public function getAllActive() {
        $products = $this->productRepository->getAllActive();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    public function getByIdActive($productId) {
        $product = $this->productRepository->getByIdActive($productId);
        // return gettype($product->);
        if ($product === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $product,
        ], 200);
    }

    public function getByCategoryIdActive($categoryId) {
        $products = $this->productRepository->getByCategoryIdActive($categoryId);

        if ($products === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $products,
        ], 200);
    }

    // Search Product
    public function searchProduct($keyword) {
        return $this->productRepository->searchProduct($keyword);
        // return response()->json([
        //     'message' => 'success',
        //     'data' => $this->productRepository->searchProduct($keyword),
        // ], 200);
    }

    // Lọc Gọng kính
    public function filterFrames($request) {
        $query = Product::query();

        $query->where('products.status', 'active');

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
    public function filterLenses($request) {
        $query = Product::query();

        $query->where('products.status', 'active');

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

    public function getBestSellingProducts($limit = 10) {
        return $this->productRepository->getBestSellingProducts($limit);
    }

    public function getNewestProducts($limit = 10) {
        return $this->productRepository->getNewestProducts($limit);
    }

    public function getProductCatetoryID($productId) {
        return $this->productRepository->getProductCatetoryID($productId);
    }
}
