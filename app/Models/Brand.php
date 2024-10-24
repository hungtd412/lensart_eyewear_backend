<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'brand';

    protected $fillable = ['name', 'status'];
}
