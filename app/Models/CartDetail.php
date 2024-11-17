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
}
