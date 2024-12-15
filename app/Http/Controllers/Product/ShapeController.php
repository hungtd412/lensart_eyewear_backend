<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductAttributesRequest;
use App\Services\Product\ShapeService;

class ShapeController extends Controller
{
    protected $shapeService;

    public function __construct(ShapeService $shapeService)
    {
        $this->shapeService = $shapeService;
    }

    public function store(StoreProductAttributesRequest $request)
    {
        return $this->shapeService->store($request->validated());
    }

    public function index()
    {
        return $this->shapeService->getAll();
    }

    public function getById($id)
    {
        return $this->shapeService->getById($id);
    }

    public function update(StoreProductAttributesRequest $request, $id)
    {
        return $this->shapeService->update($request->validated(), $id);
    }

    public function switchStatus($id)
    {
        return $this->shapeService->switchStatus($id);
    }
    public function indexActive()
    {
        return $this->shapeService->getAllActive();
    }

    public function getByIdActive($id)
    {
        return $this->shapeService->getByIdActive($id);
    }
}
