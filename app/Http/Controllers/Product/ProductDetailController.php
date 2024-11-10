<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductDetailService;
use Illuminate\Http\Request;

class ProductDetailController extends Controller {
    protected $productDetailService;

    public function __construct(ProductDetailService $productDetailService) {
        $this->productDetailService = $productDetailService;
    }

    public function store(Request $request) {
        return $this->productDetailService->store($request);
    }

    public function index() {
        return $this->productDetailService->getAll();
    }

    public function getById($id) {
        return $this->productDetailService->getById($id);
    }

    public function getByProductId($id) {
        return $this->productDetailService->getByProductId($id);
    }

    public function getByBranchId($id) {
        return $this->productDetailService->getByBranchId($id);
    }

    public function getByProductAndBranchId($productId, $branchId) {
        return $this->productDetailService->getByProductAndBranchId($productId, $branchId);
    }

    public function update(Request $request, $id) {
        return $this->productDetailService->update($request, $id);
    }
}
