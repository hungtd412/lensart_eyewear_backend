<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model {
    use HasFactory;

    protected $table = 'wishlists';

    // Các cột có thể điền vào (fillable)
    protected $fillable = [
        'user_id'
    ];

    // Tắt timestamps nếu không sử dụng
    public $timestamps = false;

    // Định nghĩa quan hệ với WishlistDetail
    public function details() {
        return $this->hasMany(WishlistDetail::class, 'wishlist_id');
    }

    // Định nghĩa quan hệ với User
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
