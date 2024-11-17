<?php

namespace App\Repositories;
use Illuminate\Support\Collection;

interface CartDetailReposityInterface {
    public function getAllCartDetails(): Collection;
}
