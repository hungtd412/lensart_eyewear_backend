<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FeatureController extends Controller {
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
            $feature = Feature::create($validated);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo Feature mới thành công!',
            'feature' => $feature
        ], 200);
    }

    public function createMultiple(Request $request) {
        try {
            $validated = $request->validate([
                'features' => 'required|array',
                'features.*.name' => 'required|string|min:2|max:50',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $createdFeatures = [];

        try {
            foreach ($validated['features'] as $featureData) {
                $feature = Feature::create($featureData);
                $createdFeatures[] = $feature;
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo các features mới thành công!',
            'features' => $createdFeatures
        ], 200);
    }

    public function index() {
        try {
            $features = Feature::all();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'features' => $features
        ], 200);
    }

    public function getById($id) {
        try {
            $feature = Feature::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'feature' => $feature
        ], 200);
    }

    public function update(Request $request, $id) {
        try {
            $feature = Feature::findOrFail($id);
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

        $feature->update($validated);

        return response()->json([
            'status' => 'success',
            'feature' => $feature
        ], 200);
    }
}
