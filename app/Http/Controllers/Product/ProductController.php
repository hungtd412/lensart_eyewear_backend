<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller {
    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'description' => 'nullable|string|max:500',
                'brand_id' => 'required|integer|min:1|exists:brands,id',
                'category_id' => 'required|integer|min:1|exists:category,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        try {
            $product = Product::create($validated);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo sản phẩm mới thành công!',
            'product' => $product
        ], 200);
    }

    public function index() {
        try {
            $products = Product::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'products' => $products
        ], 200);
    }

    public function getById($id) {
        try {
            $product = Product::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'product' => $product
        ], 200);
    }

    public function update(Request $request, $id) {
        try {
            $product = Product::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'description' => 'nullable|string|max:500',
                'brand_id' => 'required|integer|min:1|exists:brands,id',
                'category_id' => 'required|integer|min:1|exists:category,id',
                'status' => 'required|in:active,inactive'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $product->update($validated);

        return response()->json([
            'status' => 'success',
            'product' => $product
        ], 200);
    }

    public function updateEach(Request $request, $id, $attributeOfProduct) {
        return $this->productService->updateEach($request, $id, $attributeOfProduct);
    }

    public function delete($id) {
        try {
            $product = Product::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        $product->status = 'inactive';
        $product->save();

        return response()->json([
            'status' => 'success',
            'product' => $product
        ], 200);
    }
}
