<?php

namespace App\Providers;

use App\Repositories\BlogReposity;
use App\Repositories\BlogReposityInterface;
use App\Repositories\BranchRepository;
use App\Repositories\BranchRepositoryInterface;
use App\Repositories\CartDetailReposity;
use App\Repositories\CartDetailReposityInterface;
use App\Repositories\CouponRepository;
use App\Repositories\CouponRepositoryInterface;
use App\Repositories\DashboardRepository;
use App\Repositories\DashboardRepositoryInterface;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderDetailRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\OTPRepository;
use App\Repositories\OTPRepositoryInterface;
use App\Repositories\PayOSTransRepository;
use App\Repositories\PayOSTransRepositoryInterface;
use App\Repositories\Product\BrandRepository;
use App\Repositories\Product\BrandRepositoryInterface;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\CategoryRepositoryInterface;
use App\Repositories\Product\ColorRepository;
use App\Repositories\Product\ColorRepositoryInterface;
use App\Repositories\Product\FeatureRepository;
use App\Repositories\Product\FeatureRepositoryInterface;
use App\Repositories\Product\MaterialRepository;
use App\Repositories\Product\MaterialRepositoryInterface;
use App\Repositories\Product\ProductDetailRepository;
use App\Repositories\Product\ProductDetailRepositoryInterface;
use App\Repositories\Product\ProductFeatureRepository;
use App\Repositories\Product\ProductFeatureRepositoryInterface;
use App\Repositories\Product\ProductImageRepository;
use App\Repositories\Product\ProductImageRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Product\ShapeRepository;
use App\Repositories\Product\ShapeRepositoryInterface;
use App\Repositories\ProductReviewRepositoryInterface;
use App\Repositories\ProductReviewReposity;
use App\Repositories\ProductReviewReposityInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\WishlistRepository;
use App\Repositories\WishlistRepositoryInterface;
use App\Repositories\WishlistReposityInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);

        $this->app->bind(ProductFeatureRepositoryInterface::class, ProductFeatureRepository::class);

        $this->app->bind(ProductImageRepositoryInterface::class, ProductImageRepository::class);

        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(ProductDetailRepositoryInterface::class, ProductDetailRepository::class);

        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);

        $this->app->bind(ColorRepositoryInterface::class, ColorRepository::class);

        $this->app->bind(MaterialRepositoryInterface::class, MaterialRepository::class);

        $this->app->bind(ShapeRepositoryInterface::class, ShapeRepository::class);

        $this->app->bind(BranchRepositoryInterface::class, BranchRepository::class);

        $this->app->bind(BlogReposityInterface::class, BlogReposity::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

        $this->app->bind(OrderDetailRepositoryInterface::class, OrderDetailRepository::class);

        $this->app->bind(CartDetailReposityInterface::class, CartDetailReposity::class);

        $this->app->bind(BlogReposityInterface::class, BlogReposity::class);

        $this->app->bind(WishlistRepositoryInterface::class, WishlistRepository::class);

        $this->app->bind(OTPRepositoryInterface::class, OTPRepository::class);

        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);

        $this->app->bind(PayOSTransRepositoryInterface::class, PayOSTransRepository::class);

        $this->app->bind(ProductReviewReposityInterface::class, ProductReviewReposity::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
