<?php

namespace App\Services\Product;

use App\Repositories\Product\CategoryRepositoryInterface;

class CategoryService {
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function store($data) {
        $category = $this->categoryRepository->store($data);

        return response()->json([
            'status' => 'success',
            'category' => $category
        ], 200);
    }

    public function getAll() {
        $categories = $this->categoryRepository->getAll();

        return response()->json([
            'status' => 'success',
            'category' => $categories
        ], 200);
    }

    public function getById($id) {
        $category = $this->categoryRepository->getById($id);

        if ($category === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ]);
        }

        return response()->json([
            'message' => 'success',
            'category' => $category,
        ]);
    }

    public function update($data, $id) {
        $category = $this->categoryRepository->getById($id);

        $this->categoryRepository->update($data, $category);

        return response()->json([
            'message' => 'success',
            'category' => $category
        ], 200);
    }

    public function switchStatus($id) {
        $category = $this->categoryRepository->getById($id);

        $this->categoryRepository->switchStatus($category);

        return response()->json([
            'message' => 'success',
            'category' => $category
        ], 200);
    }
}
