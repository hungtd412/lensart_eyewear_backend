<?php

namespace App\Repositories\Product;

use App\Models\Feature;

class FeatureRepository implements FeatureRepositoryInterface
{
    public function store(array $feature): Feature
    {
        return Feature::create($feature);
    }

    public function getAll()
    {
        return Feature::all();
    }

    public function getById($id)
    {
        return Feature::find($id);
    }

    public function update(array $data, $feature)
    {
        $feature->update($data);
    }

    public function switchStatus($feature)
    {
        $feature->status = $feature->status == 'active' ? 'inactive' : 'active';
        $feature->save();
    }

    public function getAllActive()
    {
        return Feature::where('status', 'active')->get();
    }

    public function getByIdActive($id)
    {
        return Feature::where('id', $id)->where('status', 'active')->first();
    }
}
