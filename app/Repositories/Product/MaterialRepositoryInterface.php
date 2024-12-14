<?php

namespace App\Repositories\Product;

interface MaterialRepositoryInterface
{
    public function store(array $material);
    public function getAll();
    public function getById($id);
    public function update(array $data, $material);
    public function switchStatus($material);

    public function getAllActive();

    public function getByIdActive($id);
}
