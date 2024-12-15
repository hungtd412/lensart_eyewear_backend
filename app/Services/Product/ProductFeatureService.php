<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductFeatureRepositoryInterface;

class ProductFeatureService {
    protected $productFeatureRepository;

    public function __construct(ProductFeatureRepositoryInterface $productFeatureRepository) {
        $this->productFeatureRepository = $productFeatureRepository;
    }

    public function store($productId, $features) {
        foreach ($features as $feature) {
            $data['product_id'] = $productId;
            $data['feature_id'] = $feature;
            $this->productFeatureRepository->store($data);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }


    public function getAll() {
        $productFeatures = $this->productFeatureRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $productFeatures
        ], 200);
    }

    public function getById($id) {
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

    public function getByProductId($id) {
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

    public function update($productId, $features) {
        $this->deleteExistFeaturesByProductId($productId);

        foreach ($features as $feature) {
            $data['product_id'] = $productId;
            $data['feature_id'] = $feature;
            $this->productFeatureRepository->store($data);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }


    public function deleteExistFeaturesByProductId($productId) {
        $this->productFeatureRepository->deleteByProductId($productId);
    }

    public function delete($id) {
        $productFeature = $this->productFeatureRepository->getById($id);

        $this->productFeatureRepository->delete($productFeature);

        return response()->json([
            'message' => 'success',
            'data' => $productFeature
        ], 200);
    }

    public function getAllActive() {
        $productFeatures = $this->productFeatureRepository->getAllActive();

        return response()->json([
            'status' => 'success',
            'data' => $productFeatures
        ], 200);
    }

    public function getByIdActive($id) {
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

    public function getByProductIdActive($id) {
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
