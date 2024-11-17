<?php

namespace App\Repositories;

interface OrderDetailRepositoryInterface {
    public function store(array $data);
    public function getAll();
    public function getById(array $id);
    public function getByOrderId(array $orderId);
    public function update(array $data, $order);
    public function switchStatus($order);
}
