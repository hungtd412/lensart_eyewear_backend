<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['product_id', 'image_url', 'image_public_id',  'status'];
}
