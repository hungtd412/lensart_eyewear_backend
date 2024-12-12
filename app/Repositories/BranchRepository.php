<?php

namespace App\Repositories;

use App\Models\Branch;

class BranchRepository implements BranchRepositoryInterface {
    public function store(array $branch): Branch {
        return Branch::create($branch);
    }

    public function getAll() {
        return Branch::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
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
}
