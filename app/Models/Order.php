<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'total_price',
        'date',
        'branch_id',
        'payment_id',
        'payment_status',
        'order_date',
        'paid_date',
        'status'
    ];
}
