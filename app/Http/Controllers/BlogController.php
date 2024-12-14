<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller {
    protected $blogService;

    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
    }

    public function store(StoreBlogRequest $request) {
        return $this->blogService->store($request->validated());
    }

    public function getBlogs() {
        $blogs = $this->blogService->getActiveBlogs(10);

        return response()->json([
            'status' => 'success',
            'blogs' => $blogs
        ], 200);
    }

    public function index() {
        return $this->blogService->getAll();
    }

    public function getById($id) {
        return $this->blogService->getById($id);
    }

    public function update(StoreBlogRequest $request, $id) {
        return $this->blogService->update($request->validated(), $id);
    }

    public function switchStatus($id) {
        return $this->blogService->switchStatus($id);
    }
}
