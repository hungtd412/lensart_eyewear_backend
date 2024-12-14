<?php

namespace App\Services;

use App\Repositories\ProductReviewReposityInterface;
use Carbon\Carbon;

class ProductReviewService
{
    protected $productReviewRepository;

    public function __construct(ProductReviewReposityInterface $productReviewRepository)
    {
        $this->productReviewRepository = $productReviewRepository;
    }

    public function store($data)
    {
        $data = [
            'product_id' => $data['product_id'],
            'user_id' => $data['user_id'],
            'rating' => $data['rating'],
            'review' => $data['review']
        ];

        $productReview = $this->productReviewRepository->store($data);

        return response()->json([
            'status' => 'success',
            'data' => $productReview
        ], 200);
    }

    public function getAll()
    {
        $productReviews = $this->productReviewRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $productReviews
        ], 200);
    }

    public function getById($id)
    {
        $productReview = $this->productReviewRepository->getById($id);

        if ($productReview === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $productReview
        ], 200);
    }

    public function update($data, $id)
    {
        $productReview = $this->productReviewRepository->getById($id);

        if ($productReview === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        $this->productReviewRepository->update($data, $productReview);

        return response()->json([
            'message' => 'success',
            'data' => $productReview
        ], 200);
    }

    public function switchStatus($id)
    {
        $productReview = $this->productReviewRepository->getById($id);

        if ($productReview === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        $this->productReviewRepository->switchStatus($productReview);
    }

    public function delete($id)
    {
        $productReview = $this->productReviewRepository->getById($id);

        if ($productReview === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        $this->productReviewRepository->delete($productReview);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function getAllActive()
    {
        $productReviews = $this->productReviewRepository->getAllActive();

        return response()->json([
            'status' => 'success',
            'data' => $productReviews
        ], 200);
    }
}
