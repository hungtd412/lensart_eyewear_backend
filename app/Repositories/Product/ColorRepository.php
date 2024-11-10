<?php

namespace App\Repositories\Product;

use App\Models\Color;

class ColorRepository implements ColorRepositoryInterface {
    public function store(array $color): Color {
        return Color::create($color);
    }

    public function getAll() {
        return Color::orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")->get();
    }

    public function getById($id) {
        return Color::find($id);
    }

    public function update(array $data, $color) {
        $color->update($data);
    }

    public function switchStatus($color) {
        $color->status = $color->status == 'active' ? 'inactive' : 'active';
        $color->save();
    }
}
