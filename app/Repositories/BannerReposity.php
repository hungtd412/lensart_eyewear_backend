<?php

namespace App\Repositories;

use App\Models\Banner;

class BannerReposity implements BannerReposityInterface {
    public function store(array $banner) {
        return Banner::create($banner);
    }

    public function get() {
        return Banner::first();
    }

    public function getActive() {
        return Banner::first()->where('status', 'active');
    }

    public function update(array $data, $banner) {
        $banner->update($data);
    }

    public function switchStatus($banner) {
        $banner->status = $banner->status == 'active' ? 'inactive' : 'active';
        $banner->save();
    }
}
