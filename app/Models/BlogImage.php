<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogImage extends Model {
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['blog_id', 'image_url', 'image_public_id'];
}
