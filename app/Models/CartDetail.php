<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartDetail extends Model
{
    use HasFactory;

    protected $table = 'cart_details';
    public $timestamps = false;
    protected $fillable = [
        'cart_id',
        'product_id',
        'branch_id',
        'color',
        'quantity',
        'total_price',
    ];

    // Quan hệ với bảng `products`
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Quan hệ với bảng `branches`
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // Quan hệ với bảng `carts`
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
