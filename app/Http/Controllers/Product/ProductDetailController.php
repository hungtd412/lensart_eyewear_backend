<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductDetailAllBranchRequest;
use App\Http\Requests\Product\StoreProductDetailRequest;
use App\Http\Requests\Product\UpdateProducDetailRequest;
use App\Services\BranchService;
use App\Services\Product\ProductDetailService;

class ProductDetailController extends Controller {
    protected $productDetailService;
    protected $branchService;

    public function __construct(ProductDetailService $productDetailService, BranchService $branchService) {
        $this->productDetailService = $productDetailService;
        $this->branchService = $branchService;
    }

    public function store(StoreProductDetailRequest $request) {
        return $this->productDetailService->store($request->validated());
    }

    public function storeForAllBranch(StoreProductDetailAllBranchRequest $request) {
        $allBranches = $this->branchService->getAll();

        //convert to array
        $data = $allBranches->getData(true);
        $idAllBranches = array_column($data['data'], 'id');

        return $this->productDetailService->storeForAllBranch($request->validated(), $idAllBranches);
    }

    public function index() {
        return $this->productDetailService->getAll();
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

    public function update(UpdateProducDetailRequest $request, $productId, $branchId, $color) {
        return $this->productDetailService->update($request->validated(), $productId, $branchId, $color);
    }

    public function indexActive() {
        return $this->productDetailService->getAllActive();
    }

    public function getByProductIdActive($id) {
        return $this->productDetailService->getByProductIdActive($id);
    }

    public function getByBranchIdActive($id) {
        return $this->productDetailService->getByBranchIdActive($id);
    }

    public function getByProductAndBranchIdActive($productId, $branchId) {
        return $this->productDetailService->getByProductAndBranchIdActive($productId, $branchId);
    }
}
