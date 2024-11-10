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

    public function update(StoreProductRequest $request, $id) {
        return $this->productService->update($request->validated(), $id);
    }

    public function updateEach(Request $request, $id, $attributeOfProduct) {
        return $this->productService->updateEach($request, $id, $attributeOfProduct);
    }

    public function switchStatus($id) {
        return $this->productService->switchStatus($id);
    }
}
