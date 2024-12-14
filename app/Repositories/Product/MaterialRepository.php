<?php

namespace App\Repositories\Product;

use App\Models\Material;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function store(array $material): Material
    {
        return Material::create($material);
    }

    public function getAll()
    {
        return Material::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id)
    {
        return Material::find($id);
    }

    public function update(array $data, $material)
    {
        $material->update($data);
    }

    public function switchStatus($material)
    {
        $material->status = $material->status == 'active' ? 'inactive' : 'active';
        $material->save();
    }

    public function getAllActive()
    {
        return Material::where('status', 'active')->get();
    }

    public function getByIdActive($id)
    {
        return Material::where('id', $id)->where('status', 'active')->first();
    }
}
