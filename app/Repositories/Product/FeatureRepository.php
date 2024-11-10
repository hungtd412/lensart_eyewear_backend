<?php

namespace App\Repositories\Product;

use App\Models\Feature;

class FeatureRepository implements FeatureRepositoryInterface {
    public function store(array $feature): Feature {
        return Feature::create($feature);
    }

    public function getAll() {
        return Feature::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Feature::find($id);
    }

    public function update(array $data, $feature) {
        $feature->update($data);
    }

    public function switchStatus($feature) {
        $feature->status = $feature->status == 'active' ? 'inactive' : 'active';
        $feature->save();
    }
}
