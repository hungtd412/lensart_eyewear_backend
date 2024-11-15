<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'total_price'
    ];
}
