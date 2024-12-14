<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductFeatureRepositoryInterface;

class ProductFeatureService
{
    protected $productFeatureRepository;

    public function __construct(ProductFeatureRepositoryInterface $productFeatureRepository)
    {
        $this->productFeatureRepository = $productFeatureRepository;
    }

    public function store($data)
    {
        $productFeature = $this->productFeatureRepository->store($data->toArray());

        return response()->json([
            'status' => 'success',
            'data' => $productFeature
        ], 200);
    }

    public function getAll()
    {
        $productFeatures = $this->productFeatureRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $productFeatures
        ], 200);
    }

    public function getById($id)
    {
        $productFeature = $this->productFeatureRepository->getById($id);

        if ($productFeature === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $productFeature,
        ], 200);
    }

    public function getByProductId($id)
    {
        $productFeatures = $this->productFeatureRepository->getByProductId($id);

        if ($productFeatures === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $productFeatures,
        ], 200);
    }

    public function update($data, $id)
    {
        $productFeature = $this->productFeatureRepository->getById($id);

        $this->productFeatureRepository->update($data->toArray(), $productFeature);

        return response()->json([
            'message' => 'success',
            'data' => $productFeature
        ], 200);
    }

    public function delete($id)
    {
        $productFeature = $this->productFeatureRepository->getById($id);

        $this->productFeatureRepository->delete($productFeature);

        return response()->json([
            'message' => 'success',
            'data' => $productFeature
        ], 200);
    }

    public function getAllActive()
    {
        $productFeatures = $this->productFeatureRepository->getAllActive();

        return response()->json([
            'status' => 'success',
            'data' => $productFeatures
        ], 200);
    }

    public function getByIdActive($id)
    {
        $productFeature = $this->productFeatureRepository->getByIdActive($id);

        if ($productFeature === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $productFeature,
        ], 200);
    }

    public function getByProductIdActive($id)
    {
        $productFeatures = $this->productFeatureRepository->getByProductIdActive($id);

        if ($productFeatures === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $productFeatures,
        ], 200);
    }
}
