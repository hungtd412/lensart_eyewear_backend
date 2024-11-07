<?php

namespace App\Repositories\Product;

use App\Models\Brand;

class BrandRepository implements BrandRepositoryInterface {
    public function store(array $brand): Brand {
        return Brand::create($brand);
    }

    public function getAll() {
        return Brand::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Brand::find($id);
    }

    public function update(array $data, $brand) {
        $brand->update($data);
    }

    public function switchStatus($brand) {
        $brand->status = $brand->status == 'active' ? 'inactive' : 'active';
        $brand->save();
    }
}
