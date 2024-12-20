<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['id', 'product_id', 'branch_id', 'color', 'quantity', 'status'];

    protected $casts = [
        'color' => 'nullable',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
