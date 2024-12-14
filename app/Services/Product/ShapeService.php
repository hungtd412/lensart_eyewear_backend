<?php

namespace App\Services\Product;

use App\Repositories\Product\ShapeRepositoryInterface;

class ShapeService {
    protected $shapeRepository;

    public function __construct(ShapeRepositoryInterface $shapeRepository) {
        $this->shapeRepository = $shapeRepository;
    }

    public function store($data) {
        $shape = $this->shapeRepository->store($data);

        return response()->json([
            'status' => 'success',
            'data' => $shape
        ], 200);
    }

    public function getAll() {
        $shapes = $this->shapeRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $shapes
        ], 200);
    }

    public function getById($id) {
        $shape = $this->shapeRepository->getById($id);

        if ($shape === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $shape,
        ], 200);
    }

    public function update($data, $id) {
        $shape = $this->shapeRepository->getById($id);

        $this->shapeRepository->update($data, $shape);

        return response()->json([
            'message' => 'success',
            'data' => $shape
        ], 200);
    }

    public function switchStatus($id) {
        $shape = $this->shapeRepository->getById($id);

        $this->shapeRepository->switchStatus($shape);

        return response()->json([
            'message' => 'success',
            'data' => $shape
        ], 200);
    }
}
