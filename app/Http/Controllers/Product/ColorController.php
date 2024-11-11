<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductAttributesRequest;
use App\Services\Product\ColorService;

class ColorController extends Controller {
    protected $colorService;

    public function __construct(ColorService $colorService) {
        $this->colorService = $colorService;
    }

    public function store(StoreProductAttributesRequest $request) {
        return $this->colorService->store($request->validated());
    }

    public function index() {
        return $this->colorService->getAll();
    }

    public function getById($id) {
        return $this->colorService->getById($id);
    }

    public function update(StoreProductAttributesRequest $request, $id) {
        return $this->colorService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->colorService->switchStatus($id);
    }
}
