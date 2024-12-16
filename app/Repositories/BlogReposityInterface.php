<?php

namespace App\Repositories;

interface BlogReposityInterface {
    public function store(array $blog);
    public function getAll();
    public function getById($id);
    public function update(array $data, $blog);
    public function switchStatus($blog);
    public function getAllActive($limit);
    public function delete($blog);
}
