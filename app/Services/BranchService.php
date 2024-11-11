<?php

namespace App\Services;

use App\Repositories\BranchRepositoryInterface;

class BranchService {
    protected $branchRepository;

    public function __construct(BranchRepositoryInterface $branchRepository) {
        $this->branchRepository = $branchRepository;
    }

    public function store($data) {
        $branch = $this->branchRepository->store($data);

        return response()->json([
            'status' => 'success',
            'branch' => $branch
        ], 200);
    }

    public function getAll() {
        $branches = $this->branchRepository->getAll();

        return response()->json([
            'status' => 'success',
            'branches' => $branches
        ], 200);
    }

    public function getById($id) {
        $branch = $this->branchRepository->getById($id);

        if ($branch === null) {
            return response()->json([
                'message' => 'Can not find any data matching these conditions!'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'branch' => $branch,
        ], 200);
    }

    public function update($data, $id) {
        $branch = $this->branchRepository->getById($id);

        $this->branchRepository->update($data, $branch);

        return response()->json([
            'message' => 'success',
            'branch' => $branch
        ], 200);
    }

    public function switchStatus($id) {
        $branch = $this->branchRepository->getById($id);

        $this->branchRepository->switchStatus($branch);

        return response()->json([
            'message' => 'success',
            'branch' => $branch
        ], 200);
    }
}
