<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'image_public_id',
        'created_time',
        'status',
    ];

    protected $casts = [
        'created_time' => 'datetime:Y-m-d H:i:s',
    ];
}
