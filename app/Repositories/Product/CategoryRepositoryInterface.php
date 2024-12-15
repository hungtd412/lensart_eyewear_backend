<?php

namespace App\Repositories\Product;

interface CategoryRepositoryInterface
{
    public function store(array $category);
    public function getAll();
    public function getById(array $id);
    public function update(array $data, $category);
    public function switchStatus($category);

    public function getAllActive();

    public function getByIdActive($id);
}
