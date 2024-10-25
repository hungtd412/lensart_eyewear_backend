<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Shape;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShapeController extends Controller
{
    public function create(Request $request)
    {
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
            $shape = Shape::create($validated);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo Shape mới thành công!',
            'shape' => $shape
        ], 200);
    }

    public function createMultiple(Request $request)
    {
        try {
            $validated = $request->validate([
                'shapes' => 'required|array',
                'shapes.*.name' => 'required|string|min:2|max:50',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $createdShapes = [];

        try {
            foreach ($validated['shapes'] as $shapeData) {
                $shape = Shape::create($shapeData);
                $createdShapes[] = $shape;
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo các shapes mới thành công!',
            'shapes' => $createdShapes
        ], 200);
    }

    public function index()
    {
        try {
            $shapes = Shape::all();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'shapes' => $shapes
        ], 200);
    }

    public function getById($id)
    {
        try {
            $shape = Shape::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'shape' => $shape
        ], 200);
    }

    // public function getByName($name)
    // {
    //     try {
    //         $shape = Shape::where('name', $name)->firstOrFail();
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //         ], 422);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'shape' => $shape
    //     ], 200);
    // }

    public function update(Request $request, $id)
    {
        try {
            $shape = Shape::findOrFail($id);
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

        $shape->update($validated);

        return response()->json([
            'status' => 'success',
            'shape' => $shape
        ], 200);
    }
}
