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

    public function store(Request $request) {
        return $this->productFeatureService->store($request);
    }

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
        return $this->productFeatureService->update($request, $id);
    }

    public function delete($id) {
        return $this->productFeatureService->delete($id);
    }
}
