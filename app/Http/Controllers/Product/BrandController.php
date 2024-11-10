<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductAttributesRequest;
use App\Services\Product\BrandService;

class BrandController extends Controller {
    protected $brandService;

    public function __construct(BrandService $brandService) {
        $this->brandService = $brandService;
    }

    public function store(StoreProductAttributesRequest $request) {
        return $this->brandService->store($request->validated());
    }

    public function index() {
        return $this->brandService->getAll();
    }

    public function getById($id) {
        return $this->brandService->getById($id);
    }

    public function update(StoreProductAttributesRequest $request, $id) {
        return $this->brandService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->brandService->switchStatus($id);
    }
}
