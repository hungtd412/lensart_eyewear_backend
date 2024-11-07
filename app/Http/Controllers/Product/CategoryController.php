<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Product\CategoryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller {
    protected $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function create(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'description' => 'required|string|min:2|max:224'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        try {
            $category = Category::create($validated);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo category mới thành công!',
            'category' => $category
        ], 200);
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
        try {
            $categories = Category::all();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ], 200);
    }

    public function getById($id) {
        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'category' => $category
        ], 200);
    }

    public function update(Request $request, $id) {
        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'description' => 'required|string|min:2|max:224'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'category' => $category
        ], 200);
    }

    public function switchStatus($id) {
        return $this->categoryService->switchStatus($id);
    }
}
