<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'color',
        'quantity',
        'total_price',
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productDetails() {
        return $this->belongsTo(ProductDetail::class);
    }
}
