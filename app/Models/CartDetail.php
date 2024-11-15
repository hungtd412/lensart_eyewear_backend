<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartDetail extends Model
{
    use HasFactory;

    // Khai báo tên bảng (tùy chọn nếu tên bảng khác với tên model)
    protected $table = 'cart_details';

    // Tắt timestamps nếu không sử dụng
    public $timestamps = false;

    // Đặt khóa chính phức hợp
    protected $primaryKey = ['cart_id', 'product_id', 'branch_id', 'color'];
    public $incrementing = false;
    protected $keyType = 'string';

    // Các cột có thể điền vào (fillable)
    protected $fillable = [
        'cart_id',
        'product_id',
        'branch_id',
        'color',
        'quantity',
        'total_price',
    ];

    public function getTotalPriceAttribute()
    {
        return $this->quantity * ($this->product->offer_price ?? $this->product->price);
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
