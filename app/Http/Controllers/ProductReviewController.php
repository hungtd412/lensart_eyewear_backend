<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductReviewRequest;
use App\Services\ProductReviewService;

class ProductReviewController extends Controller
{
    protected $productReviewService;

    public function __construct(ProductReviewService $productReviewService)
    {
        $this->productReviewService = $productReviewService;
    }

    public function store(StoreProductReviewRequest $request)
    {
        return $this->productReviewService->store($request->validated());
    }

    public function index()
    {
        return $this->productReviewService->getAll();
    }

    public function getById($id)
    {
        return $this->productReviewService->getById($id);
    }

    public function update(StoreProductReviewRequest $request, $id)
    {
        return $this->productReviewService->update($request->validated(), $id);
    }

    public function switchStatus($id)
    {
        return $this->productReviewService->switchStatus($id);
    }

    public function delete($id)
    {
        return $this->productReviewService->delete($id);
    }

    public function getAllActive()
    {
        return $this->productReviewService->getAllActive();
    }
}
