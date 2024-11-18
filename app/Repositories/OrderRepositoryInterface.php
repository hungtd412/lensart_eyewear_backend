<?php

namespace App\Repositories;

interface OrderRepositoryInterface {
    public function store(array $data);
    public function getAll();
    public function getById(array $id);
    public function update(array $data, $order);
    public function switchStatus($order);
}
