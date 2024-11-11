<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductDetailRequest;
use App\Http\Requests\Product\UpdateProducDetailRequest;
use App\Services\BranchService;
use App\Services\Product\ProductDetailService;
use Illuminate\Http\Request;

class ProductDetailController extends Controller {
    protected $productDetailService;
    protected $branchService;

    public function __construct(ProductDetailService $productDetailService, BranchService $branchService) {
        $this->productDetailService = $productDetailService;
        $this->branchService = $branchService;
    }

    public function store(StoreProductDetailRequest $request) {
        return $this->productDetailService->store($request);
    }

    public function storeForAllBranch(StoreProductDetailRequest $request) {
        $allBranches = $this->branchService->getAll();

        //convert to array
        $data = $allBranches->getData(true);
        $idAllBranches = array_column($data['branches'], 'id');;
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

    public function update(UpdateProducDetailRequest $request, $productId, $branchId, $colorId) {
        return $this->productDetailService->update($request->validated(), $productId, $branchId, $colorId);
    }
}
