<?php

namespace App\Repositories;

interface ProductReviewReposityInterface
{
    public function store(array $productReview);

    public function getAll();

    public function getById($id);

    public function update(array $data, $productReview);

    public function switchStatus($productReview);

    public function getAllActive();

    public function delete($productReview);
}
