<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Repositories\Category\CategoryRepositoryInterface::class,
            \App\Repositories\Category\CategoryRepository::class,

            \App\Repositories\Product\ProductRepositoryInterface::class,
            \App\Repositories\Product\ProductRepository::class,
            \App\Repositories\Product\ProductOptionRepositoryInterface::class,
            \App\Repositories\Product\ProductOptionRepository::class,

            \App\Repositories\Cart\CartRepositoryInterface::class,
            \App\Repositories\Cart\CartRepository::class,
            \App\Repositories\Cart\CartItemRepositoryInterface::class,
            \App\Repositories\Cart\CartItemRepository::class,

            \App\Repositories\Order\OrderRepositoryInterface::class,
            \App\Repositories\Order\OrderRepository::class,
            \App\Repositories\Order\OrderItemRepositoryInterface::class,
            \App\Repositories\Order\OrderItemRepository::class,

            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url)
    {
        URL::forceScheme('https');
    }
}
