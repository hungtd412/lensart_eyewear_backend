<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'discount_price',
        'status',
        'quantity'
    ];
}
