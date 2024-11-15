<?php

namespace App\Repositories;

interface OrderRepositoryInterface {
    public function store(array $order);
    public function getAll();
    public function getById(array $order);
    public function update(array $data, $order);
    public function switchStatus($order);
}
