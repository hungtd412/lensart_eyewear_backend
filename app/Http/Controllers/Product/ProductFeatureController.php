<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductFeatureService;
use Illuminate\Http\Request;

class ProductFeatureController extends Controller {
    protected $productFeatureService;

    public function __construct(ProductFeatureService $productFeatureService) {
        $this->productFeatureService = $productFeatureService;
    }

    // public function store(Request $request) {
    //     return $this->productFeatureService->store($request);
    // }

    public function index() {
        return $this->productFeatureService->getAll();
    }

    public function getById($id) {
        return $this->productFeatureService->getById($id);
    }

    public function getByProductId($id) {
        return $this->productFeatureService->getByProductId($id);
    }

    public function update(Request $request, $id) {
        return $this->productFeatureService->update();
    }

    public function delete($id) {
        return $this->productFeatureService->delete($id);
    }

    public function indexActive() {
        return $this->productFeatureService->getAllActive();
    }

    public function getByIdActive($id) {
        return $this->productFeatureService->getByIdActive($id);
    }

    public function getByProductIdActive($id) {
        return $this->productFeatureService->getByProductIdActive($id);
    }
}
