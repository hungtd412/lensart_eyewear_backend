<?php

namespace App\Repositories\Product;

use App\Models\Brand;

class BrandRepository implements BrandRepositoryInterface
{
    public function store(array $brand): Brand
    {
        return Brand::create($brand);
    }

    public function getAll()
    {
        return Brand::all();
    }

    public function getById($id)
    {
        return Brand::find($id);
    }

    public function update(array $data, $brand)
    {
        $brand->update($data);
    }

    public function switchStatus($brand)
    {
        $brand->status = $brand->status == 'active' ? 'inactive' : 'active';
        $brand->save();
    }

    public function getAllActive()
    {
        return Brand::where('status', 'active')->get();
    }
    public function getByIdActive($id)
    {
        return Brand::where('id', $id)->where('status', 'active')->first();
    }
}
