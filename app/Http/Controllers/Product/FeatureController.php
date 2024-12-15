<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductAttributesRequest;
use App\Services\Product\FeatureService;

class FeatureController extends Controller {
    protected $featureService;

    public function __construct(FeatureService $featureService) {
        $this->featureService = $featureService;
    }

    public function store(StoreProductAttributesRequest $request) {
        return $this->featureService->store($request->validated());
    }

    public function index() {
        return $this->featureService->getAll();
    }

    public function getById($id) {
        return $this->featureService->getById($id);
    }

    public function update(StoreProductAttributesRequest $request, $id) {
        return $this->featureService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->featureService->switchStatus($id);
    }

    public function indexActive() {
        return $this->featureService->getAllActive();
    }

    public function getByIdActive($id) {
        return $this->featureService->getByIdActive($id);
    }
}
