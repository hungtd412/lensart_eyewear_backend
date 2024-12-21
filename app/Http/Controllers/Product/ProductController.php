<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Services\Product\ProductFeatureService;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller {
    protected $productService;
    protected $productFeatureService;

    public function __construct(ProductService $productService, ProductFeatureService $productFeatureService) {
        $this->productService = $productService;
        $this->productFeatureService = $productFeatureService;
    }

    public function store(StoreProductRequest $request) {
        $data = $request->validated();
        if ($data['gender'] == null) {
            $data['gender'] = 'unisex';
        }

        $product = $this->productService->store($data)->getData()->data;


        if (!empty($data['features'])) {
            $this->productFeatureService->store($product->id, $data['features']);
        }

        return $product;
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
        $data = $request->validated();

        if (!empty($data['features'])) {
            $this->productFeatureService->update($id, $data['features']);
        }

        return $this->productService->update($request->validated(), $id);
    }

    public function updateEach(Request $request, $id, $attributeOfProduct) {
        return $this->productService->updateEach($request, $id, $attributeOfProduct);
    }

    public function switchStatus($id) {
        return $this->productService->switchStatus($id);
    }

    public function indexActive() {
        return $this->productService->getAllActive();
    }

    public function getByIdActive($id) {
        return $this->productService->getByIdActive($id);
    }

    public function getByCategoryIdActive($categoryId) {
        return $this->productService->getByCategoryIdActive($categoryId);
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
