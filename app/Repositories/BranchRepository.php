<?php

namespace App\Repositories;

use App\Models\Branch;

class BranchRepository implements BranchRepositoryInterface {
    public function store(array $branch): Branch {
        return Branch::create($branch);
    }

    public function getAll() {
        $branches = Branch::with(['manager'])->get();

        return $branches->map(function ($branch) {
            return [
                'id' => $branch->id,
                'branch_name' => $branch->address,
                'review' => $branch->address,
                'manager_name' => $branch->manager->firstname . ' ' . $branch->manager->lastname,
                'status' => $branch->status,
            ];
        });
    }

    public function getById($id) {
        return Branch::find($id);
    }

    public function update(array $data, $branch) {
        $branch->update($data);
    }

    public function switchStatus($branch) {
        $branch->status = $branch->status == 'active' ? 'inactive' : 'active';
        $branch->save();
    }

    public function getAllActive() {
        return Branch::where('status', 'active')->get();
    }

    public function getByIdActive($id) {
        return Branch::where('id', $id)->where('status', 'active')->first();
    }
}
