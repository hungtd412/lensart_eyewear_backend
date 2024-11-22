<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'product_id, branch_id, color';
    public $incrementing = false;

    protected $fillable = ['product_id', 'branch_id', 'color', 'quantity', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
