<?php

namespace App\Services;

use App\Repositories\WishlistRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    protected $wishlistRepository;

    public function __construct(WishlistRepositoryInterface $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    public function getUserWishlist()
    {
        $userId = Auth::id();
        return $this->wishlistRepository->getByUserId($userId);
    }

    public function addProductToWishlist($data)
    {
        $data['user_id'] = Auth::id();
        return $this->wishlistRepository->store($data);
    }

    public function removeProductFromWishlist($wishlistDetailId)
    {
        return $this->wishlistRepository->delete($wishlistDetailId);
    }

    public function clearUserWishlist()
    {
        $userId = Auth::id();
        return $this->wishlistRepository->clearByUserId($userId);
    }

    public function moveProductToCart($wishlistDetailId)
    {
        return $this->wishlistRepository->moveProductToCart($wishlistDetailId);
    }

    public function moveAllToCart()
    {
        return $this->wishlistRepository->moveAllToCart();
    }
}
