<?php

namespace App\Services\Product;

use App\Repositories\Product\FeatureRepositoryInterface;

class FeatureService {
    protected $featureRepository;

    public function __construct(FeatureRepositoryInterface $featureRepository) {
        $this->featureRepository = $featureRepository;
    }

    public function store($data) {
        $feature = $this->featureRepository->store($data);

        return response()->json([
            'status' => 'success',
            'feature' => $feature
        ], 200);
    }

    public function getAll() {
        $features = $this->featureRepository->getAll();

        return response()->json([
            'status' => 'success',
            'features' => $features
        ], 200);
    }

    public function getById($id) {
        $feature = $this->featureRepository->getById($id);

        if ($feature === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'feature' => $feature,
        ], 200);
    }

    public function update($data, $id) {
        $feature = $this->featureRepository->getById($id);

        $this->featureRepository->update($data, $feature);

        return response()->json([
            'message' => 'success',
            'feature' => $feature
        ], 200);
    }

    public function switchStatus($id) {
        $feature = $this->featureRepository->getById($id);

        $this->featureRepository->switchStatus($feature);

        return response()->json([
            'message' => 'success',
            'feature' => $feature
        ], 200);
    }
}
