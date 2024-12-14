<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller {
    protected $productService;

    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    public function store(StoreProductRequest $request) {
        return $this->productService->store($request->validated());
    }

    public function index() {
        return $this->productService->getAll();
    }

    public function getById($id) {
        return $this->productService->getById($id);
    }

    public function getByCategoryId($categoryId) {
        return $this->productService->getByCategoryId($categoryId);
    }

    public function update(StoreProductRequest $request, $id) {
        return $this->productService->update($request->validated(), $id);
    }

    public function updateEach(Request $request, $id, $attributeOfProduct) {
        return $this->productService->updateEach($request, $id, $attributeOfProduct);
    }

    public function switchStatus($id) {
        return $this->productService->switchStatus($id);
    }

    // Search Product
    public function searchProduct(Request $request) {
        $keyword = $request->input('keyword'); // Lấy từ khóa từ request
        $products = $this->productService->searchProduct($keyword); // Gọi service

        return response()->json(['data' => $products], 200);
    }


    // Lọc Gọng kính
    public function filterFrames(Request $request) {
        $products = $this->productService->filterFrames($request);
        return response()->json([
            'status' => 'success',
            'products' => $products
        ], 200);
    }

    // Lọc Tròng kính
    public function filterLenses(Request $request) {
        $products = $this->productService->filterLenses($request);
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    public function getBestSellingProducts() {
        $products = $this->productService->getBestSellingProducts(10);
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    public function getNewestProducts() {
        $products = $this->productService->getNewestProducts(10);
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }
}
