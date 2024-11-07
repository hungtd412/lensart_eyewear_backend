<?php

namespace App\Repositories\Product;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface {
    public function store(array $category): Category {
        return Category::create($category);
    }

    public function getAll() {
        return Category::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Category::find($id);
    }

    public function update(array $data, $category) {
        $category->update($data);
    }

    public function switchStatus($category) {
        $category->status = $category->status == 'active' ? 'inactive' : 'active';
        $category->save();
    }
}
