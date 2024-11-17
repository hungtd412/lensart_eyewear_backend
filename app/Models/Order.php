<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'date',
        'branch_id',
        'address',
        'note',
        'coupon_id',
        'total_price',

        'order_status',
        'payment_status',
        'status'
    ];
}
