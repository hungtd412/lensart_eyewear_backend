<?php

namespace App\Repositories\Product;

use App\Models\Shape;

class ShapeRepository implements ShapeRepositoryInterface
{
    public function store(array $shape): Shape
    {
        return Shape::create($shape);
    }

    public function getAll()
    {
        return Shape::all();
    }

    public function getById($id)
    {
        return Shape::find($id);
    }

    public function update(array $data, $shape)
    {
        $shape->update($data);
    }

    public function switchStatus($shape)
    {
        $shape->status = $shape->status == 'active' ? 'inactive' : 'active';
        $shape->save();
    }

    public function getAllActive()
    {
        return Shape::where('status', 'active')->get();
    }

    public function getByIdActive($id)
    {
        return Shape::where('id', $id)->where('status', 'active')->first();
    }
}
