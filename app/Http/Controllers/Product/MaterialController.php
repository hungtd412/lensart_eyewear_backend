<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductAttributesRequest;
use App\Services\Product\MaterialService;

class MaterialController extends Controller {
    protected $materialService;

    public function __construct(MaterialService $materialService) {
        $this->materialService = $materialService;
    }

    public function store(StoreProductAttributesRequest $request) {
        return $this->materialService->store($request->validated());
    }

    public function index() {
        return $this->materialService->getAll();
    }

    public function getById($id) {
        return $this->materialService->getById($id);
    }

    public function update(StoreProductAttributesRequest $request, $id) {
        return $this->materialService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->materialService->switchStatus($id);
    }

    public function indexActive() {
        return $this->materialService->getAllActive();
    }

    public function getByIdActive($id) {
        return $this->materialService->getByIdActive($id);
    }
}
