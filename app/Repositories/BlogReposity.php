<?php

namespace App\Repositories;

use App\Models\Blog;

class BlogReposity implements BlogReposityInterface {
    public function store(array $blog) {
        return Blog::create($blog);
    }

    public function getAll() {
        return Blog::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Blog::find($id);
    }

    public function update(array $data, $blog) {
        $blog->update($data);
    }

    public function switchStatus($blog) {
        $blog->status = $blog->status == 'active' ? 'inactive' : 'active';
        $blog->save();
    }

    public function getAllActive($limit = 10) {
        return Blog::where('status', 'active')
            ->orderBy('id', 'desc')
            ->take($limit)
            ->first();
    }

    public function delete($blog) {
        $blog->delete();
    }
}
