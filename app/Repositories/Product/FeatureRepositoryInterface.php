<?php

namespace App\Repositories\Product;

interface FeatureRepositoryInterface {
    public function store(array $feature);
    public function getAll();
    public function getById(array $id);
    public function update(array $data, $feature);
    public function switchStatus($feature);
}
