<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ColorController extends Controller
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
            $color = Color::create($validated);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo Color mới thành công!',
            'color' => $color
        ], 200);
    }

    public function createMultiple(Request $request)
    {
        try {
            $validated = $request->validate([
                'colors' => 'required|array',
                'colors.*.name' => 'required|string|min:2|max:50',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $createdColors = [];

        try {
            foreach ($validated['colors'] as $colorData) {
                $color = Color::create($colorData);
                $createdColors[] = $color;
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo các màu mới thành công!',
            'colors' => $createdColors
        ], 200);
    }


    public function index()
    {
        try {
            $colors = Color::all();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'colors' => $colors
        ], 200);
    }

    public function getById($id)
    {
        try {
            $color = Color::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'color' => $color
        ], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $color = Color::findOrFail($id);
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

        $color->update($validated);

        return response()->json([
            'status' => 'success',
            'color' => $color
        ], 200);
    }
}
