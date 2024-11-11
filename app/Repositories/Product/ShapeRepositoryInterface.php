<?php

namespace App\Repositories\Product;

interface ShapeRepositoryInterface {
    public function store(array $shape);
    public function getAll();
    public function getById(array $shape);
    public function update(array $data, $shape);
    public function switchStatus($shape);
}
