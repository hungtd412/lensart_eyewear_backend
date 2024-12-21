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
        'payment_method',
        'status'
    ];

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class)
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->select('order_details.*', 'products.name', 'products.price', 'products.offer_price');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }

    public function payOsTrans() {
        return $this->belongsTo(PayOSTrans::class);
    }
}
