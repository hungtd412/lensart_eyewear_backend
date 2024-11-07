<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MaterialController extends Controller {
    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:50'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        try {
            $material = Material::create($validated);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo Material mới thành công!',
            'material' => $material
        ], 200);
    }

    public function createMultiple(Request $request) {
        try {
            $validated = $request->validate([
                'materials' => 'required|array',
                'materials.*.name' => 'required|string|min:2|max:50',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $createdMaterials = [];

        try {
            foreach ($validated['materials'] as $materialData) {
                $material = Material::create($materialData);
                $createdMaterials[] = $material;
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo các materials mới thành công!',
            'materials' => $createdMaterials
        ], 200);
    }

    public function index() {
        try {
            $material = Material::all();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'materials' => $material
        ], 200);
    }

    public function getById($id) {
        try {
            $material = Material::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'material' => $material
        ], 200);
    }

    // public function getByName($name)
    // {
    //     try {
    //         $material = Material::where('name', $name)->firstOrFail();
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //         ], 422);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'material' => $material
    //     ], 200);
    // }

    public function update(Request $request, $id) {
        try {
            $material = Material::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:50',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $material->update($validated);

        return response()->json([
            'status' => 'success',
            'material' => $material
        ], 200);
    }
}
