<?php

namespace App\Repositories\Product;

interface ColorRepositoryInterface {
    public function store(array $color);
    public function getAll();
    public function getById(array $id);
    public function update(array $data, $color);
    public function switchStatus($color);
}
