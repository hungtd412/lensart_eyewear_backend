<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistDetail extends Model {
    use HasFactory;

    protected $table = 'wishlist_details';

    // Các cột có thể điền vào (fillable)
    protected $fillable = [
        'wishlist_id',
        'product_id'
    ];

    // Tắt timestamps nếu không sử dụng
    public $timestamps = false;

    // Định nghĩa quan hệ với Wishlist
    public function wishlist() {
        return $this->belongsTo(Wishlist::class, 'wishlist_id');
    }

    // Định nghĩa quan hệ với Product
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
