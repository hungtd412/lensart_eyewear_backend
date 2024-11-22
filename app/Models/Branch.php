<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'address', 'manager_id', 'index', 'status'];

    protected $casts = [
        'index' => 'float',
    ];
}
