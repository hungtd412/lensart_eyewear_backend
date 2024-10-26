<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'description', 'brand_id', 'category_id', 'status'];
}
