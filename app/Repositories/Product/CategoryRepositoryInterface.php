<?php

namespace App\Repositories\Product;

interface CategoryRepositoryInterface {
    public function store(array $category);
    public function getAll();
    public function getById(array $category);
    public function update(array $data, $category);
    public function switchStatus($category);
}
