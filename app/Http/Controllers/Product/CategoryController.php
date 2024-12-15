<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreCategoryRequest;
use App\Models\Category;
use App\Services\Product\CategoryService;

class CategoryController extends Controller {
    protected $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function store(StoreCategoryRequest $request) {
        return $this->categoryService->store($request->validated());
    }

    // public function createMultiple(Request $request) {
    //     try {
    //         $validated = $request->validate([
    //             'categories' => 'required|array',
    //             'categories.*.name' => 'required|string|min:2|max:100',
    //             'categories.*.description' => 'required|string|min:2|max:224'
    //         ]);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'status' => 'fail',
    //             'errors' => $e->validator->errors(),
    //         ], 422);
    //     }

    //     $createdCategories = [];

    //     try {
    //         foreach ($validated['categories'] as $categoryData) {
    //             $category = Category::create($categoryData);
    //             $createdCategories[] = $category;
    //         }
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => 'fail',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Tạo các categories mới thành công!',
    //         'categories' => $createdCategories
    //     ], 200);
    // }



    public function index() {
        return $this->categoryService->getAll();
    }

    public function getById($id) {
        return $this->categoryService->getById($id);
    }

    public function update(StoreCategoryRequest $request, $id) {
        return $this->categoryService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->categoryService->switchStatus($id);
    }

    public function indexActive() {
        return $this->categoryService->getAllActive();
    }

    public function getByIdActive($id) {
        return $this->categoryService->getByIdActive($id);
    }
}
