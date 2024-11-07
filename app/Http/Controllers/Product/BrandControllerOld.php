<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BrandControllerOld extends Controller {
    //add policy for create, update, delete => allow admin only
    // ====> use policy or gate , add it to route for clean

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        try {
            $brand = Brand::create($validated);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo brand mới thành công!',
            'brand' => $brand
        ], 200);
    }

    public function createMultiple(Request $request) {
        try {
            $validated = $request->validate([
                'brands' => 'required|array',
                'brands.*.name' => 'required|string|min:2|max:100'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $createdBrands = [];

        try {
            foreach ($validated['brands'] as $brandData) {
                $brand = Brand::create($brandData);
                $createdBrands[] = $brand;
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo các brands mới thành công!',
            'brands' => $createdBrands
        ], 200);
    }

    public function index() {
        try {
            //active appear first
            $brands = Brand::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'brands' => $brands
        ], 200);
    }

    public function getById($id) {
        try {
            $brand = Brand::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'brand' => $brand
        ], 200);
    }

    // public function getByName($name)
    // {
    //     try {
    //         $brand = Brand::where('name', $name)->firstOrFail();
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //         ], 422);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'brand' => $brand
    //     ], 200);
    // }

    public function update(Request $request, $id) {
        try {
            $brand = Brand::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'status' => 'required|in:active,inactive'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $brand->update($validated);

        return response()->json([
            'status' => 'success',
            'brand' => $brand
        ], 200);
    }

    public function delete($id) {
        try {
            $brand = Brand::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        $brand->status = 'inactive';
        $brand->save();

        return response()->json([
            'status' => 'success',
            'brand' => $brand
        ], 200);
    }
}
