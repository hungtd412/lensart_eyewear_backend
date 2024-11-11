<?php

namespace App\Services\Product;

use App\Repositories\Product\ShapeRepositoryInterface;

class ShapeService {
    protected $ShapeRepository;

    public function __construct(ShapeRepositoryInterface $ShapeRepository) {
        $this->ShapeRepository = $ShapeRepository;
    }

    public function store($data) {
        $Shape = $this->ShapeRepository->store($data);

        return response()->json([
            'status' => 'success',
            'Shape' => $Shape
        ], 200);
    }

    public function getAll() {
        $Shapes = $this->ShapeRepository->getAll();

        return response()->json([
            'status' => 'success',
            'Shapes' => $Shapes
        ], 200);
    }

    public function getById($id) {
        $Shape = $this->ShapeRepository->getById($id);

        if ($Shape === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'Shape' => $Shape,
        ], 200);
    }

    public function update($data, $id) {
        $Shape = $this->ShapeRepository->getById($id);

        $this->ShapeRepository->update($data, $Shape);

        return response()->json([
            'message' => 'success',
            'Shape' => $Shape
        ], 200);
    }

    public function switchStatus($id) {
        $Shape = $this->ShapeRepository->getById($id);

        $this->ShapeRepository->switchStatus($Shape);

        return response()->json([
            'message' => 'success',
            'Shape' => $Shape
        ], 200);
    }
}
