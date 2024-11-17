<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function getBlogs()
    {
        $blogs = $this->blogService->getActiveBlogs(10);

        return response()->json([
            'status' => 'success',
            'blogs' => $blogs
        ], 200);
    }
}
