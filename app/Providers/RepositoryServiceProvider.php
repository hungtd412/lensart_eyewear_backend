<?php

namespace App\Providers;

use App\Repositories\Product\BrandRepository;
use App\Repositories\Product\BrandRepositoryInterface;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\CategoryRepositoryInterface;
use App\Repositories\Product\FeatureRepository;
use App\Repositories\Product\FeatureRepositoryInterface;
use App\Repositories\Product\ProductDetailRepository;
use App\Repositories\Product\ProductDetailRepositoryInterface;
use App\Repositories\Product\ProductFeatureRepository;
use App\Repositories\Product\ProductFeatureRepositoryInterface;
use App\Repositories\Product\ProductImageRepository;
use App\Repositories\Product\ProductImageRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);

        $this->app->bind(ProductFeatureRepositoryInterface::class, ProductFeatureRepository::class);

        $this->app->bind(ProductImageRepositoryInterface::class, ProductImageRepository::class);

        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(ProductDetailRepositoryInterface::class, ProductDetailRepository::class);

        $this->app->bind(FeatureRepositoryInterface::class, FeatureRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        //
    }
}
