<?php

namespace App\Repositories\Product;

interface BrandRepositoryInterface {
    public function store(array $feature);
    public function getAll();
    public function getById(array $feature);
    public function update(array $data, $feature);
    public function switchStatus($feature);
}
