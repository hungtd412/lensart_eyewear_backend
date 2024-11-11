<?php

namespace App\Repositories;

interface BranchRepositoryInterface {
    public function store(array $branch);
    public function getAll();
    public function getById(array $branch);
    public function update(array $data, $branch);
    public function switchStatus($branch);
}
