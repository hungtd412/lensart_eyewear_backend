<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductImageRequest;
use App\Services\Product\ProductImageService;

class ProductImageController extends Controller
{
    protected $productImageService;

    public function __construct(ProductImageService $productImageService)
    {
        $this->productImageService = $productImageService;
    }

    public function store(StoreProductImageRequest $request)
    {
        return $this->productImageService->store($request->validated());
    }

    public function index()
    {
        return $this->productImageService->getAll();
    }

    public function getById($id)
    {
        return $this->productImageService->getById($id);
    }

    public function getByProductId($id)
    {
        return $this->productImageService->getByProductId($id);
    }

    public function update(StoreProductImageRequest $request, $id)
    {
        return $this->productImageService->update($request->validated(), $id);
    }

    public function delete($id)
    {
        return $this->productImageService->delete($id);
    }

    public function indexActive()
    {
        return $this->productImageService->getAllActive();
    }

    public function getByIdActive($id)
    {
        return $this->productImageService->getByIdActive($id);
    }

    public function getByProductIdActive($id)
    {
        return $this->productImageService->getByProductIdActive($id);
    }
}
