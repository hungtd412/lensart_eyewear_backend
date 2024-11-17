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
}
