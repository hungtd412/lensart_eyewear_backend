<?php

namespace App\Services\Product;

use App\Repositories\Product\ColorRepositoryInterface;

class ColorService {
    protected $colorRepository;

    public function __construct(ColorRepositoryInterface $colorRepository) {
        $this->colorRepository = $colorRepository;
    }

    public function store($data) {
        $color = $this->colorRepository->store($data);

        return response()->json([
            'status' => 'success',
            'data' => $color
        ], 200);
    }

    public function getAll() {
        $colors = $this->colorRepository->getAll();

        return response()->json([
            'status' => 'success',
            'data' => $colors
        ], 200);
    }

    public function getById($id) {
        $color = $this->colorRepository->getById($id);

        if ($color === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $color,
        ], 200);
    }

    public function update($data, $id) {
        $color = $this->colorRepository->getById($id);

        $this->colorRepository->update($data, $color);

        return response()->json([
            'message' => 'success',
            'data' => $color
        ], 200);
    }

    public function switchStatus($id) {
        $color = $this->colorRepository->getById($id);

        $this->colorRepository->switchStatus($color);

        return response()->json([
            'message' => 'success',
            'data' => $color
        ], 200);
    }
}
