<?php

namespace App\Repositories;

use App\Models\ProductReview;

class ProductReviewReposity implements ProductReviewReposityInterface
{
    public function store(array $productReview)
    {
        return ProductReview::create($productReview);
    }

    public function getAll()
    {
        $reviews = ProductReview::with(['product', 'user'])->get();

        return $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'product_name' => $review->product->name,
                'user_name' => $review->user->firstname . ' ' . $review->user->lastname,
                'rating' => $review->rating,
                'review' => $review->review,
                'status' => $review->status,
            ];
        });
    }

    public function getById($id)
    {
        return ProductReview::find($id);
    }

    public function update(array $data, $productReview)
    {
        $productReview->update($data);
    }

    public function switchStatus($productReview)
    {
        $productReview->status = $productReview->status == 'active' ? 'inactive' : 'active';
        $productReview->save();
    }

    public function getAllActive()
    {
        return ProductReview::where('status', 'active')->get();
    }

    public function delete($productReview)
    {
        $productReview->delete();
    }
}
