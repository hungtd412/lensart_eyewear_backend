<?php

namespace App\Services\Product;

use App\Repositories\Product\BrandRepositoryInterface;

class BrandService {
    protected $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository) {
        $this->brandRepository = $brandRepository;
    }

    public function store($data) {
        $brand = $this->brandRepository->store($data);

        return response()->json([
            'status' => 'success',
            'brand' => $brand
        ], 200);
    }

    public function getAll() {
        $brands = $this->brandRepository->getAll();

        return response()->json([
            'status' => 'success',
            'brands' => $brands
        ], 200);
    }

    public function getById($id) {
        $brand = $this->brandRepository->getById($id);

        if ($brand === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'brand' => $brand,
        ], 200);
    }

    public function update($data, $id) {
        $brand = $this->brandRepository->getById($id);

        $this->brandRepository->update($data, $brand);

        return response()->json([
            'message' => 'success',
            'brand' => $brand
        ], 200);
    }

    public function switchStatus($id) {
        $brand = $this->brandRepository->getById($id);

        $this->brandRepository->switchStatus($brand);

        return response()->json([
            'message' => 'success',
            'brand' => $brand
        ], 200);
    }
}