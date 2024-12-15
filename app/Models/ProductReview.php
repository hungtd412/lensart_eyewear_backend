<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $table = 'product_reviews';

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'review',
        'status',
    ];

    public $timestamps = false;

    /**
     * Định nghĩa mối quan hệ với bảng products
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Định nghĩa mối quan hệ với bảng users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
