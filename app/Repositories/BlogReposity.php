<?php

namespace App\Repositories;

use App\Models\Blog;

class BlogReposity
{
    public function getActiveBlogs($limit = 10)
    {
        return Blog::where('status', 'active')
            ->orderBy('id', 'desc')
            ->take($limit)
            ->first();
    }
}
