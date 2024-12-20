<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBannerRequest;
use App\Services\BannerService;

class BannerController extends Controller {
    protected $bannerService;

    public function __construct(BannerService $bannerService) {
        $this->bannerService = $bannerService;
    }

    public function store(StoreBannerRequest $request) {
        return $this->bannerService->store($request->validated());
    }

    public function get() {
        return $this->bannerService->get();
    }

    public function getActive() {
        return $this->bannerService->getActive();
    }

    public function update(StoreBannerRequest $request) {
        return $this->bannerService->update($request->validated());
    }

    public function switchStatus() {
        return $this->bannerService->switchStatus();
    }
}
