<?php

namespace App\Services\Product;

use App\Repositories\Product\MaterialRepositoryInterface;

class MaterialService {
    protected $materialRepository;

    public function __construct(MaterialRepositoryInterface $materialRepository) {
        $this->materialRepository = $materialRepository;
    }

    public function store($data) {
        $material = $this->materialRepository->store($data);

        return response()->json([
            'status' => 'success',
            'data' => $material
        ], 200);
    }

    public function getAll() {
        $materials = $this->materialRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $materials
        ], 200);
    }

    public function getById($id) {
        $material = $this->materialRepository->getById($id);

        if ($material === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $material,
        ], 200);
    }

    public function update($data, $id) {
        $material = $this->materialRepository->getById($id);

        $this->materialRepository->update($data, $material);

        return response()->json([
            'message' => 'success',
            'data' => $material
        ], 200);
    }

    public function switchStatus($id) {
        $material = $this->materialRepository->getById($id);

        $this->materialRepository->switchStatus($material);

        return response()->json([
            'message' => 'success',
            'data' => $material
        ], 200);
    }

    public function getAllActive() {
        $materials = $this->materialRepository->getAllActive();

        return response()->json([
            'status' => 'success',
            'data' => $materials
        ], 200);
    }

    public function getByIdActive($id) {
        $material = $this->materialRepository->getByIdActive($id);

        if ($material === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $material,
        ], 200);
    }
}
