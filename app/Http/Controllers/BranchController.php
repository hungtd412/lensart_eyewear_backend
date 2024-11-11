<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreBranchRequest;
use App\Services\BranchService;

class BranchController extends Controller {
    protected $branchService;

    public function __construct(BranchService $branchService) {
        $this->branchService = $branchService;
    }

    public function store(StoreBranchRequest $request) {
        return $this->branchService->store($request->validated());
    }

    public function index() {
        return $this->branchService->getAll();
    }

    public function getById($id) {
        return $this->branchService->getById($id);
    }

    public function update(StoreBranchRequest $request, $id) {
        return $this->branchService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->branchService->switchStatus($id);
    }
}
