<?php

namespace App\Repositories;

interface BannerReposityInterface {
    public function store(array $banner);
    public function get();
    public function getActive();
    public function update(array $data, $banner);
    public function switchStatus($banner);
}
