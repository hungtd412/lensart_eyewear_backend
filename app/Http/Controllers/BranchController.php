<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BranchController extends Controller {
    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'address' => 'required|string|min:2|max:100',
                'manager_id' => 'required|integer|min:1|exists:users,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        try {
            $branch = Branch::create($validated);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo branch mới thành công!',
            'branch' => $branch
        ], 200);
    }

    public function index() {
        try {
            $branches = Branch::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'branches' => $branches
        ], 200);
    }

    public function getById($id) {
        try {
            $branch = Branch::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'branch' => $branch
        ], 200);
    }

    public function update(Request $request, $id) {
        try {
            $branch = Branch::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'address' => 'required|string|min:2|max:100',
                'manager_id' => 'required|integer|min:1|exists:users,id',
                'status' => 'required|in:active,inactive'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $branch->update($validated);

        return response()->json([
            'status' => 'success',
            'branch' => $branch
        ], 200);
    }

    public function delete($id) {
        try {
            $branch = Branch::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        $branch->status = 'inactive';
        $branch->save();

        return response()->json([
            'status' => 'success',
            'branch' => $branch
        ], 200);
    }
}
