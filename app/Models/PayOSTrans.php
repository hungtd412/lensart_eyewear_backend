<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayOSTrans extends Model {
    use HasFactory;
    public $timestamps = true;
    protected $table = 'payos_transactions';

    protected $fillable = ['orderCode', 'order_id', 'amount'];

    protected $casts = [
        'amount' => 'float',
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public static function getUnpaidTransactions() {
        return self::whereHas('order', function ($query) {
            $query->where('payment_status', 'Chưa thanh toán');
        })->get();
    }
}
